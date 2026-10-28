@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h1 class="page-title">Users</h1>
    <div class="flex gap-2">
        <a href="{{ route('admin.users.create', ['role' => 'Teacher']) }}" class="btn">Add teacher</a>
        <a href="{{ route('admin.users.create', ['role' => 'Student']) }}" class="btn-light">Add student</a>
    </div>
</div>

<div class="mb-4 flex gap-2 text-sm">
    <a href="{{ route('admin.users.index') }}" class="{{ !$filter ? 'badge-accent' : 'badge' }}">All</a>
    <a href="{{ route('admin.users.index', ['role' => 'Teacher']) }}" class="{{ $filter === 'Teacher' ? 'badge-accent' : 'badge' }}">Teachers</a>
    <a href="{{ route('admin.users.index', ['role' => 'Student']) }}" class="{{ $filter === 'Student' ? 'badge-accent' : 'badge' }}">Student</a>
</div>

<div class="card overflow-x-auto p-0">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="th">Name</th>
                <th class="th">Username</th>
                <th class="th">Email</th>
                <th class="th">Role</th>
                <th class="th"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($users as $u)
            <tr>
                <td class="td font-medium">{{ $u->displayName() }}</td>
                <td class="td">{{ $u->username }}</td>
                <td class="td">{{ $u->email }}</td>
                <td class="td"><span class="badge">{{ $u->role->role_name }}</span></td>
                <td class="td">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.users.edit', $u) }}" class="link">Edit</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Remove this user? They will no longer be able to log in.')">
                            @csrf @method('DELETE')
                            <button class="link-danger">Remove</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="td text-center text-slate-500">No users yet. Add a teacher or student to get started.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection