@extends('layouts.app')
@section('title', 'Course')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <h1 class="page-title">Course</h1>
    <a href="{{ route('admin.Course.create') }}" class="btn">Add course</a>
</div>

<div class="card overflow-x-auto p-0">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="th">Code</th>
                <th class="th">Course</th>
                <th class="th">Teacher</th>
                <th class="th">Student</th>
                <th class="th"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($Course as $c)
            <tr>
                <td class="td"><span class="badge-accent">{{ $c->course_code }}</span></td>
                <td class="td font-medium">{{ $c->course_name }}</td>
                <td class="td">{{ $c->teacher?->full_name ?? 'Not assigned' }}</td>
                <td class="td">{{ $c->Student_count }}</td>
                <td class="td">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.Course.show', $c) }}" class="link">Student</a>
                        <a href="{{ route('admin.Course.edit', $c) }}" class="link">Edit</a>
                        <form method="POST" action="{{ route('admin.Course.destroy', $c) }}" onsubmit="return confirm('Delete this course and its assignments?')">
                            @csrf @method('DELETE')
                            <button class="link-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="td text-center text-slate-500">No Course yet. Add your first course.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $Course->links() }}</div>
@endsection