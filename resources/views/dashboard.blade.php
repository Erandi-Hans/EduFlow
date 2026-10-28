@extends('layouts.layout')

@section('title', 'Admin Dashboard - EduFlow')

@section('content')
<div class="space-y-6">

    <!-- Hero Banner Card -->
    <div class="bg-[#164EC7] rounded-[32px] p-8 text-white relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center min-h-[220px]">
        <div class="space-y-3 z-10 max-w-lg">
            <span class="inline-block px-3.5 py-1 bg-white/15 backdrop-blur-md rounded-full text-xs font-semibold tracking-wide">
                Tuesday, 27 October
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight">Good afternoon, Admin</h1>
            <p class="text-white/80 text-sm font-medium">
                You have full control of members, courses and assignments.
            </p>
            <div class="flex items-center gap-3 pt-2">
                <button class="bg-[#FFB703] hover:bg-amber-400 text-slate-900 font-bold px-5 py-3 rounded-2xl text-sm flex items-center gap-2 transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add member
                </button>
                <button class="bg-white/15 hover:bg-white/20 text-white font-semibold px-5 py-3 rounded-2xl text-sm transition-all">
                    Manage courses
                </button>
            </div>
        </div>

        <!-- Banner Graphic Illustration -->
        <div class="hidden md:block absolute right-8 top-1/2 -translate-y-1/2 w-64 h-full pointer-events-none">
            <div class="relative w-full h-full flex items-center justify-center">
                <div class="absolute w-44 h-44 bg-white/10 rounded-full blur-xl"></div>
                <!-- Books Graphic Placeholder -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-24 h-12 bg-amber-400 rounded-t-xl mb-1 shadow-md"></div>
                    <div class="w-32 h-10 bg-slate-200 rounded-lg mb-1 shadow-md"></div>
                    <div class="w-36 h-10 bg-white rounded-lg shadow-lg"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-[24px] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#164EC7] flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-none">864</h3>
                <span class="text-xs font-semibold text-slate-400">Students</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-[24px] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-none">42</h3>
                <span class="text-xs font-semibold text-slate-400">Teachers</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-[24px] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#164EC7] flex items-center justify-center">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-none">36</h3>
                <span class="text-xs font-semibold text-slate-400">Courses</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-[24px] shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-none">118</h3>
                <span class="text-xs font-semibold text-slate-400">Assignments</span>
            </div>
        </div>
    </div>

</div>
@endsection