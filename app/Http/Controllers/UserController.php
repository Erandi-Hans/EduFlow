<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const TEACHER_FIELDS = ['first_name', 'last_name', 'phone_number', 'qualification'];
    private const STUDENT_FIELDS = ['first_name', 'last_name', 'date_of_birth', 'guardian_name'];

    public function index(Request $request)
    {
        $filter = in_array($request->query('role'), ['Teacher', 'Student'], true) ? $request->query('role') : null;

        $users = User::with(['role', 'teacher', 'student'])
            ->whereHas('role', function ($q) use ($filter) {
                $filter ? $q->where('role_name', $filter) : $q->whereIn('role_name', ['Teacher', 'Student']);
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'filter'));
    }

    public function create(Request $request)
    {
        $role = $request->query('role') === 'Student' ? 'Student' : 'Teacher';

        return view('admin.users.create', ['user' => null, 'role' => $role]);
    }

    public function store(Request $request)
    {
        $role = $request->validate(['role' => ['required', 'in:Teacher,Student']])['role'];

        $data = $request->validate(array_merge([
            'username' => ['required', 'string', 'max:60'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ], $this->profileRules($role)));

        DB::transaction(function () use ($data, $role) {
            $user = User::create([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role_id'  => Role::where('role_name', $role)->value('role_id'),
            ]);

            if ($role === 'Teacher') {
                $user->teacher()->create(array_intersect_key($data, array_flip(self::TEACHER_FIELDS)));
            } else {
                $user->student()->create(array_intersect_key($data, array_flip(self::STUDENT_FIELDS)));
            }
        });

        return redirect()->route('admin.users.index')->with('success', "$role created.");
    }

    public function edit(User $user)
    {
        $user->load(['role', 'teacher', 'student']);
        abort_if($user->hasRole('Admin'), 403);

        return view('admin.users.edit', ['user' => $user, 'role' => $user->role->role_name]);
    }

    public function update(Request $request, User $user)
    {
        $role = $user->role->role_name;
        abort_if($role === 'Admin', 403);

        $data = $request->validate(array_merge([
            'username' => ['required', 'string', 'max:60'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'password' => ['nullable', 'string', 'min:8'],
        ], $this->profileRules($role)));

        DB::transaction(function () use ($data, $user, $role) {
            $user->username = $data['username'];
            $user->email    = $data['email'];
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            if ($role === 'Teacher') {
                $user->teacher->update(array_intersect_key($data, array_flip(self::TEACHER_FIELDS)));
            } else {
                $user->student->update(array_intersect_key($data, array_flip(self::STUDENT_FIELDS)));
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    /** Soft delete: the row stays in the database with is_deleted = true. */
    public function destroy(User $user)
    {
        abort_if($user->user_id === auth()->id() || $user->hasRole('Admin'), 403, 'This account cannot be removed.');

        $user->update(['is_deleted' => true]);

        return redirect()->route('admin.users.index')->with('success', 'User removed.');
    }

    private function profileRules(string $role): array
    {
        $common = [
            'first_name' => ['required', 'string', 'max:60'],
            'last_name'  => ['required', 'string', 'max:60'],
        ];

        return $role === 'Teacher'
            ? $common + [
                'phone_number'  => ['nullable', 'string', 'max:30'],
                'qualification' => ['nullable', 'string', 'max:255'],
            ]
            : $common + [
                'date_of_birth' => ['nullable', 'date', 'before:today'],
                'guardian_name' => ['nullable', 'string', 'max:120'],
            ];
    }
}
