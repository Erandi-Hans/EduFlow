<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

/**
 * Admin: manage teachers and students (full CRUD).
 * Delete = soft delete (users.is_deleted = true), so the member disappears everywhere and cannot log in.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type') === 'students' ? 'students' : 'teachers';

        $members = $type === 'teachers'
            ? Teacher::with('user')->whereHas('user')->orderBy('first_name')->paginate(10)->withQueryString()
            : Student::with(['user', 'Course'])->whereHas('user')->orderBy('first_name')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('type', 'members'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type') === 'student' ? 'student' : 'teacher';

        return view('admin.users.create', ['type' => $type, 'user' => null, 'profile' => null]);
    }

    public function store(Request $request)
    {
        $type = $request->input('type') === 'student' ? 'student' : 'teacher';
        $data = $request->validate($this->rules($type));

        DB::transaction(function () use ($data, $type) {
            $user = User::create([
                'username'   => $data['username'],
                'email'      => $data['email'],
                'password'   => $data['password'],            // hashed by the model cast
                'role_id'    => Role::where('role_name', ucfirst($type))->value('role_id'),
                'is_deleted' => false,
            ]);

            $profile = $this->profileData($type, $data) + ['user_id' => $user->user_id];

            $type === 'teacher' ? Teacher::forceCreate($profile) : Student::forceCreate($profile);
        });

        return redirect()
            ->route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers'])
            ->with('success', ucfirst($type) . ' created.');
    }

    public function edit(User $user)
    {
        $type = $this->typeOf($user);
        $profile = $type === 'teacher' ? $user->teacher : $user->student;

        return view('admin.users.edit', compact('type', 'user', 'profile'));
    }

    public function update(Request $request, User $user)
    {
        $type = $this->typeOf($user);
        $data = $request->validate($this->rules($type, $user));

        $attrs = ['username' => $data['username'], 'email' => $data['email']];
        if (! empty($data['password'])) {
            $attrs['password'] = $data['password'];
        }

        DB::transaction(function () use ($user, $type, $attrs, $data) {
            $user->update($attrs);

            $model = $type === 'teacher' ? Teacher::class : Student::class;
            $model::where('user_id', $user->user_id)->update($this->profileData($type, $data));
        });

        return redirect()
            ->route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers'])
            ->with('success', ucfirst($type) . ' updated.');
    }

    public function destroy(User $user)
    {
        $type = $this->typeOf($user);

        $user->update(['is_deleted' => true]);   // soft delete

        return redirect()
            ->route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers'])
            ->with('success', ucfirst($type) . ' removed.');
    }

    /* ------------------------------------------------------------- helpers */

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

    private function rules(string $type, ?User $user = null): array
    {
        $email = Rule::unique('users', 'email');
        $username = Rule::unique('users', 'username');

        if ($user) {
            $email->ignore($user->user_id, 'user_id');
            $username->ignore($user->user_id, 'user_id');
        }

        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', $email],
            'username'   => ['required', 'string', 'max:100', $username],
            'password'   => $user ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
        ];

        return $rules + ($type === 'teacher'
            ? ['phone_number' => ['nullable', 'string', 'max:30'], 'qualification' => ['nullable', 'string', 'max:255']]
            : ['date_of_birth' => ['nullable', 'date', 'before:today'], 'guardian_name' => ['nullable', 'string', 'max:255']]);
    }

    private function profileData(string $type, array $data): array
    {
        $base = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];

        return $base + ($type === 'teacher'
            ? ['phone_number' => $data['phone_number'] ?? null, 'qualification' => $data['qualification'] ?? null]
            : ['date_of_birth' => $data['date_of_birth'] ?? null, 'guardian_name' => $data['guardian_name'] ?? null]);
    }
}
