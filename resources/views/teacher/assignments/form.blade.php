<form method="POST" action="{{ $assignment ? route('teacher.assignments.update', $assignment) : route('teacher.assignments.store') }}" class="card max-w-2xl space-y-4">
    @csrf
    @if ($assignment) @method('PUT') @endif

    <div>
        <label class="label" for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $assignment->title ?? '') }}" required class="input">
        @error('title') <p class="err">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="label" for="course_id">Course</label>
            <select id="course_id" name="course_id" required class="input">
                <option value="">Choose a course</option>
                @foreach ($Course as $c)
                <option value="{{ $c->course_id }}" @selected(old('course_id', $assignment->course_id ?? null) == $c->course_id)>{{ $c->course_name }}</option>
                @endforeach
            </select>
            @error('course_id') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="due_date">Due date</label>
            <input id="due_date" name="due_date" type="datetime-local"
                value="{{ old('due_date', optional($assignment?->due_date)->format('Y-m-d\TH:i')) }}" required class="input">
            @error('due_date') <p class="err">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="label" for="description">Instructions</label>
        <textarea id="description" name="description" rows="5" class="input">{{ old('description', $assignment->description ?? '') }}</textarea>
        @error('description') <p class="err">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn">{{ $assignment ? 'Save changes' : 'Create assignment' }}</button>
        <a href="{{ route('teacher.assignments.index') }}" class="btn-light">Cancel</a>
    </div>
</form>