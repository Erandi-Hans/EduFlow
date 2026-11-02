@extends('layouts.app')
@section('title', 'Edit course')

@section('content')
<p class="text-[11px] font-bold uppercase tracking-widest text-[#00786F]">Admin dashboard / Courses</p>

<div class="mb-6 mt-1">
    <h1 class="text-2xl font-extrabold text-slate-900">Edit course</h1>
    <p class="text-sm text-slate-500">Update course details or change the teacher.</p>
</div>

<div class="grid items-start gap-5 lg:grid-cols-3">

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.Course.update', $course->course_id) }}" class="lg:col-span-2">
        @csrf
        @method('PUT')
        @include('admin.courses.form')
    </form>

    {{-- Side cards --}}
    <div class="space-y-5">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex h-24 items-start justify-between p-4 text-white" style="background-image:linear-gradient(135deg,#00786F,#00948a)">
                <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold">{{ $course->course_code }}</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5zM4 19.5V21h16" />
                </svg>
            </div>
            <div class="p-4">
                <p class="text-[15px] font-bold text-slate-900">{{ $course->course_name }}</p>
                <p class="mb-3 mt-1 text-xs text-slate-500">
                    {{ $course->teacher?->full_name ?? 'No teacher yet' }} · {{ $course->students_count }} {{ \Illuminate\Support\Str::plural('student', $course->students_count) }}
                </p>
                <div class="h-2 overflow-hidden rounded-full bg-[#E6F4F2]">
                    <div class="h-full w-4/5 rounded-full bg-[#00786F]"></div>
                </div>
            </div>
            <div class="border-t border-slate-100 p-4">
                <a href="{{ route('admin.Course.show', $course->course_id) }}" class="text-sm font-bold text-[#00786F] hover:underline">Manage enrolled students</a>
            </div>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50/40 p-6">
            <h2 class="text-base font-bold text-slate-900">Delete course</h2>
            <p class="mb-4 mt-1 text-sm text-slate-500">A course with students or assignments cannot be deleted.</p>
            <form method="POST" action="{{ route('admin.Course.destroy', $course->course_id) }}"
                data-confirm-title="Delete {{ $course->course_name }}?"
                data-confirm-text="This cannot be undone. A course with students or assignments cannot be deleted."
                data-confirm-button="Yes, delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#c0392b] px-4 py-2 text-xs font-bold text-white hover:bg-[#a93226]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                    </svg>
                    Delete course
                </button>
            </form>
        </div>
    </div>
</div>

@include('layouts.partials.confirm-modal')
@endsection