@extends('layouts.app')
@section('title', $course->course_name)

@section('content')
<a href="{{ route('teacher.Course.index') }}" class="link">Back to my Course</a>
<h1 class="page-title mb-1 mt-2">{{ $course->course_name }} <span class="badge-accent ml-2">{{ $course->course_code }}</span></h1>
<p class="mb-6 text-sm text-slate-500">{{ $course->description }}</p>

<div class="card overflow-x-auto p-0">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="th">Student</th>
                <th class="th">Guardian</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($Student as $s)
            <tr>
                <td class="td font-medium">{{ $s->full_name }}</td>
                <td class="td">{{ $s->guardian_name ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="td text-center text-slate-500">No Student are enrolled in this course.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection