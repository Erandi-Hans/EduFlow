@extends('layouts.app')
@section('title', $course->course_name)

@section('content')
@php
$initials = fn ($n) => \Illuminate\Support\Str::upper(collect(explode(' ', trim($n)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));
$input = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-[#00786F] focus:outline-none focus:ring-2 focus:ring-[#00786F]/20';
@endphp

<p class="mb-2 text-[11px] font-bold uppercase tracking-widest text-[#00786F]">Admin dashboard / Courses</p>

{{-- Course banner --}}
<div class="relative overflow-hidden rounded-3xl p-7 text-white sm:p-8" style="background-image:linear-gradient(120deg,#00574f 0%,#00786F 55%,#00948a 120%)">
    <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
    <span class="inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">{{ $course->course_code }}</span>
    <h1 class="mt-3 text-2xl font-extrabold sm:text-3xl">{{ $course->course_name }}</h1>
    <p class="mt-1 text-sm text-white/85">
        {{ $course->teacher?->full_name ?? 'No teacher yet' }} · {{ $enrolled->count() }} {{ \Illuminate\Support\Str::plural('student', $enrolled->count()) }} · {{ $assignments->count() }} {{ \Illuminate\Support\Str::plural('assignment', $assignments->count()) }}
    </p>
    @if ($course->description)
    <p class="mt-2 max-w-2xl text-sm text-white/75">{{ $course->description }}</p>
    @endif
    <a href="{{ route('admin.Course.edit', $course->course_id) }}"
        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-[#00786F] shadow hover:bg-[#E6F4F2]">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
        </svg>
        Edit course
    </a>
</div>

<div class="mt-5 grid items-start gap-5 lg:grid-cols-3">

    {{-- Enrolled students --}}
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900">Enrolled students</h2>
            <span class="rounded-full bg-[#E6F4F2] px-3 py-1 text-xs font-bold text-[#00786F]">{{ $enrolled->count() }} {{ \Illuminate\Support\Str::plural('student', $enrolled->count()) }}</span>
        </div>

        <table class="w-full min-w-[420px] text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                    <th class="py-3 pr-4">Student</th>
                    <th class="py-3 pr-4">Enrolled</th>
                    <th class="py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($enrolled as $s)
                <tr>
                    <td class="py-3 pr-4">
                        <div class="flex items-center gap-3">
                            @if ($s->user->photo_url)
                            <img src="{{ $s->user->photo_url }}" alt="{{ $s->full_name }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
                            @else
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#E6F4F2] text-xs font-extrabold text-[#00786F]">{{ $initials($s->full_name) }}</span>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate font-bold text-slate-900">{{ $s->full_name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $s->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap py-3 pr-4 text-slate-700">{{ $s->pivot->created_at?->format('M d') ?? '—' }}</td>
                    <td class="py-3">
                        <div class="flex justify-end">
                            <form method="POST" action="{{ route('admin.Course.unenroll', [$course->course_id, $s->student_id]) }}"
                                data-confirm-title="Remove {{ $s->full_name }}?"
                                data-confirm-text="The student will be removed from {{ $course->course_name }}."
                                data-confirm-button="Yes, remove">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Remove from course"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-red-50 hover:text-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-10 text-center text-slate-400">No students enrolled yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right column --}}
    <div class="space-y-5">

        {{-- Enroll a student --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-bold text-slate-900">Enroll a student</h2>
            <form method="POST" action="{{ route('admin.Course.enroll', $course->course_id) }}">
                @csrf
                <label for="student_id" class="mb-2 block text-xs font-semibold text-slate-700">Student</label>
                <select id="student_id" name="student_id" class="{{ $input }}" @disabled($available->isEmpty())>
                    <option value="">{{ $available->isEmpty() ? 'All students are enrolled' : 'Select a student' }}</option>
                    @foreach ($available as $a)
                    <option value="{{ $a->student_id }}">{{ $a->full_name }}</option>
                    @endforeach
                </select>
                @error('student_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror

                <button type="submit" @disabled($available->isEmpty())
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-[#00786F] px-4 py-3 text-sm font-bold text-white hover:bg-[#00948a] disabled:cursor-not-allowed disabled:opacity-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Enroll
                </button>
            </form>
        </div>

        {{-- Assignments of this course --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-2 text-base font-bold text-slate-900">Assignments</h2>
            <div class="divide-y divide-slate-100">
                @forelse ($assignments as $a)
                <div class="flex items-center gap-3 py-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#E6F4F2] text-[#00786F]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <path d="M14 2v6h6M9 13h6M9 17h6" />
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-900">{{ $a->title }}</p>
                        <p class="text-xs text-slate-500">Due {{ \Carbon\Carbon::parse($a->due_date)->format('M d') }}</p>
                    </div>
                </div>
                @empty
                <p class="py-6 text-center text-sm text-slate-400">No assignments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.confirm-modal')
@endsection