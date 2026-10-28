@extends('layouts.app')
@section('title', 'Submissions')

@section('content')
<a href="{{ route('teacher.assignments.index') }}" class="link">Back to assignments</a>
<h1 class="page-title mb-1 mt-2">{{ $assignment->title }}</h1>
<p class="mb-6 text-sm text-slate-500">
    {{ $assignment->course->course_name }}, due {{ $assignment->due_date->format('d M Y, h:i A') }}
</p>

<div class="card overflow-x-auto p-0">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="th">Student</th>
                <th class="th">File</th>
                <th class="th">Submitted</th>
                <th class="th">Status</th>
                <th class="th">Marks (0-100)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($submissions as $s)
            <tr>
                <td class="td font-medium">{{ $s->student->full_name }}</td>
                <td class="td"><a href="{{ $s->fileUrl() }}" target="_blank" class="link">Open file</a></td>
                <td class="td">{{ $s->updated_at->format('d M, h:i A') }}</td>
                <td class="td"><span class="{{ $s->status === 'graded' ? 'badge-accent' : 'badge' }}">{{ ucfirst($s->status) }}</span></td>
                <td class="td">
                    <form method="POST" action="{{ route('teacher.submissions.grade', $s) }}" class="flex items-center gap-2">
                        @csrf @method('PATCH')
                        <input name="marks" type="number" step="0.01" min="0" max="100" value="{{ $s->marks }}" required class="input w-24">
                        <button class="btn-light">Save</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="td text-center text-slate-500">No submissions yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection