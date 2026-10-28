@extends('layouts.app')
@section('title', 'My Course')

@section('content')
<h1 class="page-title mb-5">My Course</h1>

<div class="grid gap-4 md:grid-cols-2">
    @forelse ($Course as $c)
    <div class="card">
        <div class="flex items-start justify-between gap-3">
            <h2 class="font-semibold text-slate-800">{{ $c->course_name }}</h2>
            <span class="badge-accent">{{ $c->course_code }}</span>
        </div>
        <p class="mt-2 text-sm text-slate-500">{{ $c->description ?: 'No description.' }}</p>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-sm text-slate-500">{{ $c->Student_count }} Student, {{ $c->assignments_count }} assignments</span>
            <a href="{{ route('teacher.Course.show', $c) }}" class="link">View Student</a>
        </div>
    </div>
    @empty
    <p class="text-sm text-slate-500">You have no Course yet. Ask an admin to assign one to you.</p>
    @endforelse
</div>
@endsection