@extends('layouts.app')
@section('title', 'Assignments')

@section('content')
<h1 class="page-title mb-5">My assignments</h1>

<div class="space-y-4">
    @forelse ($assignments as $a)
    @php
    $sub = $submissions[$a->assignment_id] ?? null;
    $overdue = $a->isOverdue();
    @endphp
    <div class="card">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-semibold text-slate-800">{{ $a->title }}</h2>
                <p class="text-sm text-slate-500">{{ $a->course->course_name }}</p>
            </div>
            <div class="text-right text-sm">
                <p class="{{ $overdue ? 'text-slate-400' : 'text-slate-600' }}">Due {{ $a->due_date->format('d M Y, h:i A') }}</p>
                @if ($sub)
                <span class="{{ $sub->status === 'graded' ? 'badge-accent' : 'badge' }}">
                    {{ $sub->status === 'graded' ? 'Graded: ' . rtrim(rtrim($sub->marks, '0'), '.') . '/100' : 'Submitted' }}
                </span>
                @elseif ($overdue)
                <span class="badge">Closed</span>
                @else
                <span class="badge">Not submitted</span>
                @endif
            </div>
        </div>

        @if ($a->description)
        <p class="mt-3 text-sm text-slate-600">{{ $a->description }}</p>
        @endif

        <div class="mt-4 border-t border-slate-100 pt-4">
            @if ($sub)
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ $sub->fileUrl() }}" target="_blank" class="link">View my file</a>

                @unless ($overdue)
                <form method="POST" action="{{ route('student.submissions.update', $sub) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                    @csrf
                    <input type="file" name="file" required class="text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:text-slate-700">
                    <button class="btn-light">Replace file</button>
                </form>
                <form method="POST" action="{{ route('student.submissions.destroy', $sub) }}" onsubmit="return confirm('Delete your submission?')">
                    @csrf @method('DELETE')
                    <button class="link-danger">Delete</button>
                </form>
                @endunless
            </div>
            @elseif (! $overdue)
            <form method="POST" action="{{ route('student.submissions.store', $a) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                @csrf
                <input type="file" name="file" required class="text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:text-slate-700">
                <button class="btn">Submit assignment</button>
            </form>
            @else
            <p class="text-sm text-slate-500">The deadline has passed. Submissions are closed.</p>
            @endif
        </div>
    </div>
    @empty
    <p class="text-sm text-slate-500">No assignments yet. They will appear here once you are enrolled in a course.</p>
    @endforelse
</div>
@endsection