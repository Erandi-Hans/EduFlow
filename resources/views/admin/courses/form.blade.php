<form method="POST" action="{{ $course ? route('admin.Course.update', $course) : route('admin.Course.store') }}" class="card max-w-2xl space-y-4">
    @csrf
    @if ($course) @method('PUT') @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="label" for="course_name">Course name</label>
            <input id="course_name" name="course_name" value="{{ old('course_name', $course->course_name ?? '') }}" required class="input">
            @error('course_name') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="course_code">Course code</label>
            <input id="course_code" name="course_code" value="{{ old('course_code', $course->course_code ?? '') }}" required class="input">
            @error('course_code') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <label class="label" for="teacher_id">Teacher</label>
            <select id="teacher_id" name="teacher_id" class="input">
                <option value="">Not assigned</option>
                @foreach ($teachers as $t)
                <option value="{{ $t->teacher_id }}" @selected(old('teacher_id', $course->teacher_id ?? null) == $t->teacher_id)>{{ $t->full_name }}</option>
                @endforeach
            </select>
            @error('teacher_id') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <label class="label" for="description">Description</label>
            <textarea id="description" name="description" rows="4" class="input">{{ old('description', $course->description ?? '') }}</textarea>
            @error('description') <p class="err">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn">{{ $course ? 'Save changes' : 'Create course' }}</button>
        <a href="{{ route('admin.Course.index') }}" class="btn-light">Cancel</a>
    </div>
</form>