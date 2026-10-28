<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = auth()->user()->teacher->courses()
            ->withCount(['students', 'assignments'])->orderBy('course_name')->get();

        return view('teacher.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        abort_unless($course->teacher_id === auth()->user()->teacher->teacher_id, 403);

        $students = $course->students()->whereHas('user')->orderBy('first_name')->get();

        return view('teacher.courses.show', compact('course', 'students'));
    }
}
