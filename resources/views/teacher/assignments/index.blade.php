@extends('layouts.app')
@section('title', 'Assignments')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <h1 class="page-title">Assignments</h1>
    <a href="{{ route('teacher.assignments.create') }}" class="btn">New assignment</a>
</div>

<div class="card overflow-x-auto p-0">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="th">Title</th>
                <th class="th">Course</th>
                <th class="th">Due</th>
                <th class="th">Submissions</th>
                <th class="th"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($assignments as $a)
            <tr>
                <td class="td font-medium">{{ $a->title }}</td>
                <td class="td"><span class="badge-accent">{{ $a->course->course_code }}</span></td>
                <td class="td">
                    {{ $a->due_date->format('d M Y, h:i A') }}
                    @if ($a->isOverdue()) <span class="badge ml-1">Closed</span> @endif
                </td>
                <td class="td">{{ $a->submissions_count }}</td>
                <td class="td">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('teacher.submissions.index', $a) }}" class="link">Grade</a>
                        <a href="{{ route('teacher.assignments.edit', $a) }}" class="link">Edit</a>
                        <form method="POST" action="{{ route('teacher.assignments.destroy', $a) }}" onsubmit="return confirm('Delete this assignment and all its submissions?')">
                            @csrf @method('DELETE')
                            <button class="link-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="td text-center text-slate-500">No assignments yet. Create one for your course.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $assignments->links() }}</div>
@endsection