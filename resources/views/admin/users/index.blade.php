@extends('layouts.app')
@section('title', 'Users')

@section('content')
@php
$isStudents = $type === 'students';
$initials = fn ($n) => \Illuminate\Support\Str::upper(collect(explode(' ', trim($n)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));
$iconBtn = 'flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-[#E6F4F2] hover:text-[#00786F]';
@endphp

{{-- Header --}}
<div class="mb-5 flex flex-wrap items-start justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900">Users</h1>
        <p class="text-sm text-slate-500">Manage teachers and students.</p>
    </div>
    <a href="{{ route('admin.users.create', ['type' => $isStudents ? 'student' : 'teacher']) }}"
        class="inline-flex items-center gap-2 rounded-xl bg-[#00786F] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#00948a]">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Add {{ $isStudents ? 'student' : 'teacher' }}
    </a>
</div>

{{-- Tabs --}}
<div class="mb-5 inline-flex rounded-xl bg-[#E6F4F2] p-1">
    <a href="{{ route('admin.users.index', ['type' => 'teachers']) }}"
        class="rounded-lg px-5 py-2 text-sm font-semibold {{ ! $isStudents ? 'bg-white text-[#00786F] shadow-sm' : 'text-slate-500 hover:text-[#00786F]' }}">Teachers</a>
    <a href="{{ route('admin.users.index', ['type' => 'students']) }}"
        class="rounded-lg px-5 py-2 text-sm font-semibold {{ $isStudents ? 'bg-white text-[#00786F] shadow-sm' : 'text-slate-500 hover:text-[#00786F]' }}">Students</a>
</div>

{{-- Table --}}
<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 sm:p-4">
    <table class="w-full min-w-[640px] text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                @if ($isStudents)
                <th class="px-4 py-3">Student</th>
                <th class="px-4 py-3">Course</th>
                <th class="px-4 py-3">Enrolled</th>
                @else
                <th class="px-4 py-3">Teacher</th>
                <th class="px-4 py-3">Qualification</th>
                <th class="px-4 py-3">Status</th>
                @endif
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($members as $m)
            @php
            $name = $m->full_name;
            $email = $m->user->email;
            @endphp
            <tr class="hover:bg-slate-50/60">
                <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                        @if ($m->user->profile_photo)
                        <img src="{{ $m->user->profile_photo}}" alt="{{ $name }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
                        @else
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#E6F4F2] text-xs font-extrabold text-[#00786F]">{{ $initials($name) }}</span>
                        @endif
                        <div class="min-w-0">
                            <p class="truncate font-bold text-slate-900">{{ $name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $email }}</p>
                        </div>
                    </div>
                </td>

                @if ($isStudents)
                @php
                $courses = $m->Course;
                $enrolled = $courses->map(fn ($c) => $c->pivot->created_at ?? null)->filter()->min() ?: $m->user->created_at;
                @endphp
                <td class="px-4 py-4">
                    @forelse ($courses->take(3) as $c)
                    <span class="mr-1 inline-block rounded-full bg-[#E6F4F2] px-3 py-1 text-xs font-bold text-[#00786F]">{{ $c->course_code }}</span>
                    @empty
                    <span class="text-slate-400">Not enrolled</span>
                    @endforelse
                    @if ($courses->count() > 3)
                    <span class="text-xs text-slate-500">+{{ $courses->count() - 3 }}</span>
                    @endif
                </td>
                <td class="whitespace-nowrap px-4 py-4 text-slate-700">{{ $enrolled ? \Carbon\Carbon::parse($enrolled)->format('M d') : '—' }}</td>
                @else
                <td class="px-4 py-4 text-slate-700">{{ $m->qualification ?: '—' }}</td>
                <td class="px-4 py-4"><span class="rounded-full bg-[#E6F4F2] px-3 py-1 text-xs font-bold text-[#00786F]">Active</span></td>
                @endif

                <td class="px-4 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $m->user_id) }}" aria-label="Edit" class="{{ $iconBtn }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.users.destroy', $m->user_id) }}"
                            data-confirm-title="Remove {{ $name }}?"
                            data-confirm-text="They will no longer be able to log in. This is a soft delete, so the record is kept."
                            data-confirm-button="Yes, remove">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Remove" class="{{ $iconBtn }} hover:bg-red-50 hover:text-red-600">
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
                <td colspan="4" class="px-4 py-12 text-center text-slate-400">No {{ $type }} yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($members->hasPages())
<div class="mt-4">{{ $members->links() }}</div>
@endif
@include('layouts.partials.confirm-modal')
@endsection