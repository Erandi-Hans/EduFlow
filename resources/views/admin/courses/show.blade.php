@extends('layouts.app')
@section('title', $course->course_name)

@section('content')
<a href="{{ route('admin.Course.index') }}" class="link">Back to Course</a>
<h1 class="page-title mb-1 mt-2">{{ $course->course_name }} <span class="badge-accent ml-2">{{ $course->course_code }}</span></h1>
<p class="mb-6 text-sm text-slate-500">Teacher: {{ $course->teacher?->full_name ?? 'Not assigned' }}</p>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="card overflow-x-auto p-0 lg:col-span-2">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="th">Enrolled Student</th>
                    <th class="th"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($enrolled as $s)
                <tr>
                    <td class="td">{{ $s->full_name }}</td>
                    <td class="td text-right">
                        <form method="POST" action="{{ route('admin.Course.unenroll', [$course, $s]) }}" onsubmit="return confirm('Remove this student from the course?')">
                            @csrf @method('DELETE')
                            <button class="link-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="td text-center text-slate-500">No Student enrolled yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <form method="POST" action="{{ route('admin.Course.enroll', $course) }}" class="card space-y-3 self-start">
        @csrf
        <label class="label" for="student_id">Enroll a student</label>
        <select id="student_id" name="student_id" required class="input">
            <option value="">Choose a student</option>
            @foreach ($available as $s)
            <option value="{{ $s->student_id }}">{{ $s->full_name }}</option>
            @endforeach
        </select>
        <button class="btn w-full justify-center">Enroll student</button>
    </form>
</div>
@endsection