@extends('layouts.app')
@section('title', 'Edit ' . $type)

@section('content')
@php
$name = $profile->full_name;
$initials = \Illuminate\Support\Str::upper(collect(explode(' ', trim($name)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));
@endphp

<p class="text-[11px] font-bold uppercase tracking-widest text-[#00786F]">Admin dashboard / Users</p>

<div class="mb-6 mt-1">
    <h1 class="text-2xl font-extrabold text-slate-900">Edit {{ $type }}</h1>
    <p class="text-sm text-slate-500">Update profile and account details.</p>
</div>

<div class="grid items-start gap-5 lg:grid-cols-3">

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}" class="lg:col-span-2">
        @csrf
        @method('PUT')
        @include('admin.users.form')
    </form>

    {{-- Side cards --}}
    <div class="space-y-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
            <span class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-full bg-[#E6F4F2] text-2xl font-extrabold text-[#00786F]">{{ $initials }}</span>
            <h2 class="mt-3 text-lg font-extrabold text-slate-900">{{ $name }}</h2>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>
            <span class="mt-3 inline-block rounded-full bg-[#E6F4F2] px-3 py-1 text-xs font-bold text-[#00786F]">{{ ucfirst($type) }}</span>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-2 text-base font-bold text-slate-900">Account summary</h2>
            <dl class="divide-y divide-slate-100 text-sm">
                <div class="flex justify-between py-2.5">
                    <dt class="text-slate-500">Role</dt>
                    <dd class="font-bold text-slate-900">{{ ucfirst($type) }}</dd>
                </div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-slate-500">Status</dt>
                    <dd class="font-bold text-slate-900">Active</dd>
                </div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-slate-500">Login</dt>
                    <dd class="font-bold text-slate-900">Enabled</dd>
                </div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-slate-500">Created</dt>
                    <dd class="font-bold text-slate-900">{{ $user->created_at?->format('M d') ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Soft delete --}}
        <div class="rounded-2xl border border-red-200 bg-red-50/40 p-6">
            <h2 class="text-base font-bold text-slate-900">Remove member</h2>
            <p class="mb-4 mt-1 text-sm text-slate-500">Soft delete: the member disappears from the system and can no longer log in.</p>
            <form method="POST" action="{{ route('admin.users.destroy', $user->user_id) }}"
                onsubmit="return confirm('Remove {{ addslashes($name) }}? They will no longer be able to log in.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#c0392b] px-4 py-2 text-xs font-bold text-white hover:bg-[#a93226]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
                    </svg>
                    Remove {{ $type }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection