@extends('layouts.app')
@section('title', 'Create account')

@section('content')
<p class="text-[11px] font-bold uppercase tracking-widest text-[#00786F]">Admin dashboard / Users</p>

<div class="mb-6 mt-1 flex flex-wrap items-start justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900">Create new account</h1>
        <p class="text-sm text-slate-500">Add a new {{ $type }} to the EduFlow system.</p>
    </div>

    {{-- Teacher / Student switch --}}
    <div class="inline-flex rounded-xl bg-[#E6F4F2] p-1">
        <a href="{{ route('admin.users.create', ['type' => 'teacher']) }}"
            class="rounded-lg px-5 py-2 text-sm font-semibold {{ $type === 'teacher' ? 'bg-white text-[#00786F] shadow-sm' : 'text-slate-500 hover:text-[#00786F]' }}">Teacher</a>
        <a href="{{ route('admin.users.create', ['type' => 'student']) }}"
            class="rounded-lg px-5 py-2 text-sm font-semibold {{ $type === 'student' ? 'bg-white text-[#00786F] shadow-sm' : 'text-slate-500 hover:text-[#00786F]' }}">Student</a>
    </div>
</div>

<div class="grid items-start gap-5 lg:grid-cols-3">

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.users.store') }}" class="lg:col-span-2">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        @include('admin.users.form')
    </form>

    {{-- Side cards --}}
    <div class="space-y-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center">
            <span class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-full bg-[#E6F4F2] text-3xl font-bold text-[#00786F]">+</span>
            <h2 class="mt-3 text-lg font-extrabold text-slate-900">New {{ $type }}</h2>
            <p class="text-sm text-slate-500">Profile preview</p>
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
                    <dd class="font-bold text-slate-900">Today</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection