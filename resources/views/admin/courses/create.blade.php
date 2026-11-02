@extends('layouts.app')
@section('title', 'New course')

@section('content')
<p class="text-[11px] font-bold uppercase tracking-widest text-[#00786F]">Admin dashboard / Courses</p>

<div class="mb-6 mt-1">
    <h1 class="text-2xl font-extrabold text-slate-900">New course</h1>
    <p class="text-sm text-slate-500">Create a course and assign a teacher.</p>
</div>

<div class="grid items-start gap-5 lg:grid-cols-3">

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.Course.store') }}" class="lg:col-span-2">
        @csrf
        @include('admin.courses.form')
    </form>

    {{-- Side cards --}}
    <div class="space-y-5">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex h-24 items-start justify-between p-4 text-white" style="background-image:linear-gradient(135deg,#00786F,#00948a)">
                <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-bold">CODE</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5zM4 19.5V21h16" />
                </svg>
            </div>
            <div class="p-4">
                <p class="text-[15px] font-bold text-slate-900">Course name</p>
                <p class="mb-3 mt-1 text-xs text-slate-500">Teacher · 0 students</p>
                <div class="h-2 overflow-hidden rounded-full bg-[#E6F4F2]">
                    <div class="h-full w-[8%] rounded-full bg-[#00786F]"></div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="mb-2 text-base font-bold text-slate-900">Good to know</h2>
            <dl class="divide-y divide-slate-100 text-sm">
                <div class="flex justify-between py-2.5">
                    <dt class="text-slate-500">Students</dt>
                    <dd class="font-bold text-slate-900">Enroll after saving</dd>
                </div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-slate-500">Teacher</dt>
                    <dd class="font-bold text-slate-900">Can be changed later</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@include('layouts.partials.confirm-modal')
@endsection