<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * UserController (Admin only)
 *
 * Lets the admin add, view, edit and remove teachers and students.
 * Each member can have a profile photo.
 * "Remove" is a soft delete: the user is only marked as deleted (users.is_deleted = true),
 * so the record stays in the database but the user can no longer log in.
 */
class UserController extends Controller
{
    /**
     * Show the user list.
     * The "type" in the URL decides which tab is shown: ?type=teachers or ?type=students.
     * The optional "search" in the URL filters by name, username or email.
     */
    public function index(Request $request)
    {
        // Only two values are allowed. Anything else falls back to "teachers".
        $type = $request->query('type') === 'students' ? 'students' : 'teachers';
        $search = trim((string) $request->query('search', ''));

        $query = $type === 'teachers'
            ? Teacher::with('user')
            : Student::with(['user', 'Course']);

        $members = $query
            ->whereHas('user')   // skip removed (soft deleted) users
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';

                // Match the first name, last name, "first last", or the account's email / username.
                $q->where(function ($q) use ($like) {
                    $q->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhereRaw("concat(first_name, ' ', last_name) like ?", [$like])
                        ->orWhereHas('user', fn($u) => $u->where('email', 'like', $like)->orWhere('username', 'like', $like));
                });
            })
            ->orderBy('first_name')
            ->paginate(10)            // 10 rows per page
            ->withQueryString();      // keeps ?type=... and ?search=... when the admin changes the page

        return view('admin.users.index', compact('type', 'members', 'search'));
    }


    /**
     * Show the empty "Create account" form.
     * The "type" in the URL (?type=teacher or ?type=student) decides which fields are shown.
     */
    public function create(Request $request)
    {
        $type = $request->query('type') === 'student' ? 'student' : 'teacher';

        return view('admin.users.create', ['type' => $type, 'user' => null, 'profile' => null]);
    }

    /**
     * Save a new teacher or student.
     * Steps: validate the form, save the photo (if any), then create the user and the profile together.
     */
    public function store(Request $request)
    {
        $type = $request->input('type') === 'student' ? 'student' : 'teacher';

        // Stops here and goes back to the form with error messages if the data is not valid.
        $data = $request->validate($this->rules($type));

        // The photo is saved in storage/app/public/profile-photos (needs: php artisan storage:link).
        $photo = $request->hasFile('photo')
            ? $request->file('photo')->store('profile-photos', 'public')
            : null;

        // The transaction makes sure both records are saved, or none of them.
        DB::transaction(function () use ($data, $type, $photo) {
            // 1) The login account.
            $user = User::create([
                'username'      => $data['username'],
                'email'         => $data['email'],
                'password'      => $data['password'],   // hashed automatically by the User model
                'role_id'       => Role::where('role_name', ucfirst($type))->value('role_id'),
                'is_deleted'    => false,
                'profile_photo' => $photo,
            ]);

            // 2) The teacher or student profile, linked to the account.
            $profile = $this->profileData($type, $data) + ['user_id' => $user->user_id];

            $type === 'teacher' ? Teacher::forceCreate($profile) : Student::forceCreate($profile);
        });

        return redirect()
            ->route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers'])
            ->with('success', ucfirst($type) . ' created.');
    }

    /**
     * Show the "Edit" form with the current details filled in.
     */
    public function edit(User $user)
    {
        $type = $this->typeOf($user);
        $profile = $type === 'teacher' ? $user->teacher : $user->student;

        return view('admin.users.edit', compact('type', 'user', 'profile'));
    }

    /**
     * Save the changes made in the "Edit" form.
     * An empty password box means "keep the old password".
     * A new photo replaces the old one, and "remove photo" deletes it.
     */
    public function update(Request $request, User $user)
    {
        $type = $this->typeOf($user);

        // Passing $user lets the email and username rules ignore this user's own current values.
        $data = $request->validate($this->rules($type, $user));

        $attrs = ['username' => $data['username'], 'email' => $data['email']];

        // Change the password only when something was typed.
        if (! empty($data['password'])) {
            $attrs['password'] = $data['password'];
        }

        if ($request->hasFile('photo')) {
            // A new photo was uploaded: delete the old file and keep the new one.
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $attrs['profile_photo'] = $request->file('photo')->store('profile-photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            // The admin ticked "Remove current photo".
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $attrs['profile_photo'] = null;
        }

        // Update the account and the profile together.
        DB::transaction(function () use ($user, $type, $attrs, $data) {
            $user->update($attrs);

            $model = $type === 'teacher' ? Teacher::class : Student::class;
            $model::where('user_id', $user->user_id)->update($this->profileData($type, $data));
        });

        return redirect()
            ->route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers'])
            ->with('success', ucfirst($type) . ' updated.');
    }

    /**
     * Remove a user (soft delete).
     * The record is kept in the database. The user disappears from the lists and cannot log in.
     */
    public function destroy(User $user)
    {
        $type = $this->typeOf($user);

        $user->update(['is_deleted' => true]);   // the photo file is kept

        return redirect()
            ->route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers'])
            ->with('success', ucfirst($type) . ' removed.');
    }

    /* ------------------------------------------------------------- helpers */

    /**
     * Find out if a user is a teacher or a student.
     * Admins (and any other role) cannot be managed on these pages, so we stop with an error.
     */
    private function typeOf(User $user): string
    {
        if ($user->hasRole('Teacher')) {
            return 'teacher';
        }
        if ($user->hasRole('Student')) {
            return 'student';
        }

        abort(403, 'Only teachers and students can be managed here.');
    }

    /**
     * The validation rules for the create and edit forms.
     * Pass $user when editing, so the user's own email and username are not counted as "already taken".
     */
    private function rules(string $type, ?User $user = null): array
    {
        $email = Rule::unique('users', 'email');
        $username = Rule::unique('users', 'username');

        // When editing, ignore this user's own row in the "unique" check.
        if ($user) {
            $email->ignore($user->user_id, 'user_id');
            $username->ignore($user->user_id, 'user_id');
        }

        // Rules that are the same for teachers and students.
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', $email],
            'username'   => ['required', 'string', 'max:100', $username],
            'password'   => $user ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],   // required only on create
            'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],               // max 2 MB
        ];

        // Extra rules that depend on the type.
        return $rules + ($type === 'teacher'
            ? ['phone_number' => ['nullable', 'string', 'max:30'], 'qualification' => ['nullable', 'string', 'max:255']]
            : ['date_of_birth' => ['nullable', 'date', 'before:today'], 'guardian_name' => ['nullable', 'string', 'max:255']]);
    }

    /**
     * Pick only the fields that belong to the teacher or student table.
     * (The account fields like email and password go to the users table instead.)
     */
    private function profileData(string $type, array $data): array
    {
        $base = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];

        return $base + ($type === 'teacher'
            ? ['phone_number' => $data['phone_number'] ?? null, 'qualification' => $data['qualification'] ?? null]
            : ['date_of_birth' => $data['date_of_birth'] ?? null, 'guardian_name' => $data['guardian_name'] ?? null]);
    }
}
