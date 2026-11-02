{{-- Shared form fields (left card) for create and edit.
     Needs: $course (null on create) and $teachers.
     The parent view provides the <form> tag, @csrf and (for edit) @method('PUT'). --}}
@php
$isEdit = (bool) $course;
$input = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#00786F] focus:outline-none focus:ring-2 focus:ring-[#00786F]/20';
$label = 'mb-2 block text-xs font-semibold text-slate-700';
$head = 'mb-4 flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-[#00786F]';
$num = 'flex h-6 w-6 items-center justify-center rounded-md bg-[#E6F4F2] text-[11px]';
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6">

    {{-- 1. Course details --}}
    <div class="{{ $head }}"><span class="{{ $num }}">1</span> Course details</div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="course_name" class="{{ $label }}">Course name *</label>
            <input id="course_name" name="course_name" type="text" value="{{ old('course_name', $course->course_name ?? '') }}"
                placeholder="e.g. Introduction to Programming" class="{{ $input }}">
            @error('course_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="course_code" class="{{ $label }}">Course code *</label>
            <input id="course_code" name="course_code" type="text" value="{{ old('course_code', $course->course_code ?? '') }}"
                placeholder="e.g. CS101" class="{{ $input }}">
            @error('course_code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- 2. Teacher --}}
    <div class="{{ $head }} mt-8"><span class="{{ $num }}">2</span> Teacher</div>
    <label for="teacher_id" class="{{ $label }}">Assign teacher</label>
    <select id="teacher_id" name="teacher_id" class="{{ $input }}">
        <option value="">Not assigned yet</option>
        @foreach ($teachers as $t)
        <option value="{{ $t->teacher_id }}" @selected((string) old('teacher_id', $course->teacher_id ?? '') === (string) $t->teacher_id)>{{ $t->full_name }}</option>
        @endforeach
    </select>
    @error('teacher_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror

    {{-- 3. Description --}}
    <div class="{{ $head }} mt-8"><span class="{{ $num }}">3</span> Description</div>
    <label for="description" class="{{ $label }}">About this course</label>
    <textarea id="description" name="description" rows="4" class="{{ $input }}">{{ old('description', $course->description ?? '') }}</textarea>
    @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror

    {{-- Buttons --}}
    <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-5">
        <a href="{{ route('admin.Course.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
        <button type="submit" class="rounded-xl bg-[#00786F] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#00948a]">
            {{ $isEdit ? 'Save changes' : 'Create' }}
        </button>
    </div>
</div>