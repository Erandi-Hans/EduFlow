@extends('layouts.app')
@section('title', 'Course')

@section('content')
<h1 class="page-title mb-5">Course</h1>

<div class="grid gap-4 md:grid-cols-2">
    @forelse ($Course as $c)
    <div class="card">
        <div class="flex items-start justify-between gap-3">
            <h2 class="font-semibold text-slate-800">{{ $c->course_name }}</h2>
            <span class="badge-accent">{{ $c->course_code }}</span>
        </div>
        <p class="mt-2 text-sm text-slate-500">{{ $c->description ?: 'No description.' }}</p>
        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
            <span>Teacher: {{ $c->teacher?->full_name ?? 'Not assigned' }}</span>
            @if (in_array($c->course_id, $enrolled))
            <span class="badge-accent">Enrolled</span>
            @endif
        </div>
    </div>
    @empty
    <p class="text-sm text-slate-500">No Course are available yet.</p>
    @endforelse
</div>
@endsection