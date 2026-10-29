@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 space-y-8">

    <!-- Flash Messages (Alerts) -->
    @if(session('success'))
    <div class="flex items-center justify-between bg-teal-50 border border-teal-200 text-teal-800 px-5 py-3.5 rounded-2xl shadow-sm transition-all">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->has('delete'))
    <div class="flex items-center justify-between bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-2xl shadow-sm transition-all">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-bold">{{ $errors->first('delete') }}</span>
        </div>
    </div>
    @endif

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Courses</h1>
            <p class="text-sm text-slate-500 mt-1">Create courses and assign teachers.</p>
        </div>

        <a href="{{ route('admin.Course.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-teal-700 hover:bg-teal-800 shadow-md shadow-teal-700/20 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            New course
        </a>
    </div>

    <!-- Course Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($courses as $c)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden group">

            <!-- Card Header (Teal Header Area) -->
            <div class="bg-teal-700 p-5 text-white flex items-center justify-between">
                <!-- Course Code Badge -->
                <span class="inline-block bg-teal-800/80 text-white font-extrabold text-[11px] px-3 py-1 rounded-full tracking-wider border border-teal-600/50">
                    {{ $c->course_code }}
                </span>

                <!-- Course Icon -->
                <div class="text-teal-200/80 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                <div>
                    <!-- Course Name -->
                    <a href="{{ route('admin.Course.show', $c->course_id) }}" class="block font-bold text-slate-800 text-base leading-snug hover:text-teal-700 transition-colors">
                        {{ $c->course_name }}
                    </a>

                    <!-- Teacher Name & Enrolled Student Count -->
                    <p class="text-xs text-slate-400 mt-1 font-medium">
                        {{ $c->teacher_name ?? 'Unassigned' }} · {{ $c->students_count }} students
                    </p>
                </div>

                <!-- Progress Bar (Design Match) -->
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-teal-600 h-1.5 rounded-full" style="width: {{ min(100, max(10, $c->students_count * 2)) }}%"></div>
                </div>
            </div>

            <!-- Card Footer (Active State & Actions) -->
            <div class="px-5 pb-5 pt-1 flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Active</span>

                <!-- Actions (Edit / Delete) -->
                <div class="flex items-center gap-1.5">
                    <!-- Edit Button -->
                    <a href="{{ route('admin.Course.edit', $c->course_id) }}"
                        class="p-2 text-slate-600 hover:text-teal-700 hover:bg-slate-100 rounded-xl transition-all border border-slate-200/80 shadow-xs"
                        title="Edit Course">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.Course.destroy', $c->course_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="p-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all border border-slate-200/80 shadow-xs"
                            title="Delete Course">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <p class="text-slate-500 font-bold text-sm">No courses found in the system.</p>
            <p class="text-xs text-slate-400 mt-1">Click "New course" button to add a course.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection