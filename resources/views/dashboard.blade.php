@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
$me = auth()->user();
$role = $me->role->role_name;
$hour = now()->hour;
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening' );

    [$chip, $btnLabel, $btnRoute, $blurb]=match ($role) { 'Admin'=> ['Administrator', 'Manage users', 'admin.users.index', 'Here is a quick summary of your school today.'],
    'Teacher' => ['Teacher', 'View my courses', 'teacher.Course.index', 'Here is a quick summary of your courses and assignments.'],
    default => ['Student', 'View assignments', 'student.assignments.index', 'Here is a quick summary of your courses and assignments.'],
    };

    // Admin: load the extra data here if the controller did not pass it
    if ($role === 'Admin') {
    $recentMembers = $recentMembers ?? \App\Models\User::with(['role', 'teacher', 'student'])->latest()->take(5)->get();
    $countRole = fn ($r) => \App\Models\User::whereHas('role', fn ($q) => $q->where('role_name', $r))->count();
    $membership = $membership ?? ['Students' => $countRole('Student'), 'Teachers' => $countRole('Teacher'), 'Admins' => $countRole('Admin')];

    $newMembers = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
    $needTeacher = class_exists(\App\Models\Course::class) ? \App\Models\Course::whereNull('teacher_id')->count() : 0;
    $blurb = "You have {$newMembers} new " . \Illuminate\Support\Str::plural('member', $newMembers)
    . " this week and {$needTeacher} " . \Illuminate\Support\Str::plural('course', $needTeacher) . ' needing a teacher.';
    }

    $initials = fn ($n) => \Illuminate\Support\Str::upper(collect(explode(' ', trim($n)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));

    $icons = [
    'users' => '
    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
    <circle cx="9" cy="7" r="4" />
    <path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8" />',
    'book' => '
    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5zM4 19.5V21h16" />',
    'file' => '
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
    <path d="M14 2v6h6M9 13h6M9 17h6" />',
    ];
    // Pick an icon from the stat label ("Students", "Courses", "Assignments"...)
    $iconFor = function ($label) {
    $l = strtolower($label);
    return str_contains($l, 'assign') ? 'file' : (str_contains($l, 'course') ? 'book' : 'users');
    };
    @endphp

    {{-- Welcome banner --}}
    <div class="relative overflow-hidden rounded-3xl p-7 text-white sm:p-8"
        style="background-image:linear-gradient(120deg,#00574f 0%,#00786F 55%,#00948a 120%)">
        <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
        <span class="inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">{{ $chip }}</span>
        <h1 class="mt-3 text-2xl font-extrabold sm:text-3xl">{{ $greeting }}, {{ $me->displayName() }}</h1>
        <p class="mt-1 max-w-xl text-sm text-white/85">{{ $blurb }}</p>
        <a href="{{ route($btnRoute) }}" class="mt-5 inline-block rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-[#00786F] shadow hover:bg-[#E6F4F2]">{{ $btnLabel }}</a>
    </div>

    {{-- Stat cards (uses the $stats array from DashboardController) --}}
    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($stats as $label => $value)
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E6F4F2] text-[#00786F]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons[$iconFor($label)] !!}</svg>
            </span>
            <div>
                <p class="text-2xl font-extrabold leading-tight text-slate-900">{{ $value }}</p>
                <p class="text-sm text-slate-500">{{ $label }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Admin extras: shown when the controller passes $recentMembers and $membership --}}
    @if (isset($recentMembers) || isset($membership))
    <div class="mt-5 grid gap-5 lg:grid-cols-3">

        @isset($recentMembers)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
            <h2 class="mb-2 text-base font-bold text-slate-900">Recently added members</h2>
            <div class="divide-y divide-slate-100">
                @forelse ($recentMembers as $member)
                @php $mName = $member->displayName(); @endphp
                <div class="flex items-center justify-between gap-3 py-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#E6F4F2] text-xs font-extrabold text-[#00786F]">{{ $initials($mName) }}</span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-900">{{ $mName }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $member->email }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-[#E6F4F2] px-3 py-1 text-xs font-bold text-[#00786F]">{{ $member->role->role_name }}</span>
                </div>
                @empty
                <p class="py-6 text-center text-sm text-slate-400">No members yet.</p>
                @endforelse
            </div>
        </div>
        @endisset

        @isset($membership)
        @php $maxMembers = max(1, max($membership)); @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-4 text-base font-bold text-slate-900">Membership</h2>
            <div class="space-y-5">
                @foreach ($membership as $label => $count)
                <div>
                    <div class="mb-1.5 flex justify-between text-sm">
                        <span class="font-bold text-slate-900">{{ $label }}</span>
                        <span class="text-slate-600">{{ $count }}</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-[#E6F4F2]">
                        <div class="h-full rounded-full" style="width: {{ round($count / $maxMembers * 100) }}%; background-image:linear-gradient(90deg,#00786F,#2fb5a9)"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endisset
    </div>
    @endif
    @endsection