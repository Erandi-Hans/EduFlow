@extends('layouts.app')
@section('title', 'Dashboard')

{{-- Data comes from DashboardController: $role, $banner, $stats and the role specific lists --}}
@section('content')
@php
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
'check' => '
<path d="M20 6 9 17l-5-5" />',
'upload' => '
<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />',
];
$initials = fn ($n) => \Illuminate\Support\Str::upper(collect(explode(' ', trim($n)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));
$courseColors = ['#00786F', '#0a9a8a', '#00574f', '#00948a', '#00786F'];
@endphp

{{-- Welcome banner --}}
<div class="relative overflow-hidden rounded-3xl p-7 text-white sm:p-8"
    style="background-image:linear-gradient(120deg,#00574f 0%,#00786F 55%,#00948a 120%)">
    <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
    <span class="inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">{{ $banner['chip'] }}</span>
    <h1 class="mt-3 text-2xl font-extrabold sm:text-3xl">{{ $banner['title'] }}</h1>
    <p class="mt-1 max-w-xl text-sm text-white/85">{{ $banner['text'] }}</p>
    <a href="{{ route($banner['route']) }}" class="mt-5 inline-block rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-[#00786F] shadow hover:bg-[#E6F4F2]">{{ $banner['label'] }}</a>
</div>

{{-- Stat cards --}}
<div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($stats as [$label, $value, $icon])
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5">
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E6F4F2] text-[#00786F]">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons[$icon] !!}</svg>
        </span>
        <div>
            <p class="text-2xl font-extrabold leading-tight text-slate-900">{{ $value }}</p>
            <p class="text-sm text-slate-500">{{ $label }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ================= ADMIN ================= --}}
@if ($role === 'Admin')
<div class="mt-5 grid gap-5 lg:grid-cols-3">
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
</div>

{{-- ================= TEACHER ================= --}}
@elseif ($role === 'Teacher')
<div class="mt-5 rounded-2xl border border-slate-200 bg-white p-6">
    <h2 class="mb-2 text-base font-bold text-slate-900">Latest submissions</h2>
    <div class="divide-y divide-slate-100">
        @forelse ($submissions as $s)
        <div class="flex items-center justify-between gap-3 py-3">
            <div class="flex min-w-0 items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#E6F4F2] text-xs font-extrabold text-[#00786F]">{{ $initials($s['student']) }}</span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-slate-900">{{ $s['student'] }}</p>
                    <p class="truncate text-xs text-slate-500">{{ $s['assignment'] }}</p>
                </div>
            </div>
            <a href="{{ route('teacher.submissions.index', $s['assignment_id']) }}"
                class="shrink-0 rounded-lg bg-[#00786F] px-4 py-2 text-xs font-bold text-white hover:bg-[#00948a]">Grade</a>
        </div>
        @empty
        <p class="py-6 text-center text-sm text-slate-400">Nothing waiting to be graded.</p>
        @endforelse
    </div>
</div>

{{-- ================= STUDENT ================= --}}
@else
<div class="mt-5 grid gap-5 lg:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
        <h2 class="mb-2 text-base font-bold text-slate-900">Upcoming assignments</h2>
        <div class="divide-y divide-slate-100">
            @forelse ($upcoming as $a)
            @php
            $badge = match ($a['status']) {
            'Pending' => 'bg-amber-50 text-amber-700',
            default => 'bg-[#E6F4F2] text-[#00786F]',
            };
            @endphp
            <div class="flex items-center justify-between gap-3 py-3">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#E6F4F2] text-[#00786F]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons['file'] !!}</svg>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-900">{{ $a['title'] }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $a['code'] }} · Due {{ $a['due'] }}</p>
                    </div>
                </div>
                <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $badge }}">{{ $a['status'] }}</span>
            </div>
            @empty
            <p class="py-6 text-center text-sm text-slate-400">No assignments yet.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6">
        <h2 class="mb-2 text-base font-bold text-slate-900">My courses</h2>
        <div class="divide-y divide-slate-100">
            @forelse ($myCourses as $c)
            <div class="flex items-center gap-3 py-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white" style="background-color: {{ $courseColors[$loop->index % count($courseColors)] }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons['book'] !!}</svg>
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-slate-900">{{ $c['name'] }}</p>
                    <p class="truncate text-xs text-slate-500">{{ $c['teacher'] }}</p>
                </div>
            </div>
            @empty
            <p class="py-6 text-center text-sm text-slate-400">Not enrolled in any course yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endif
@endsection