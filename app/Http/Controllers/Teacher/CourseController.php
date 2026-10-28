<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Auth;

class CourseController extends Controller
{
    public function index()
    {
        $Course = auth::user()->teacher->Course()
            ->withCount(['Student', 'assignments'])->orderBy('course_name')->get();

        return view('teacher.Courses.index', compact('Course'));
    }

    public function show(Course $course)
    {
        abort_unless($course->teacher_id === Auth::user()->teacher->teacher_id, 403);

        $Student = $course->Student()->whereHas('user')->orderBy('first_name')->get();

        return view('teacher.Course.show', compact('course', 'Student'));
    }
}
