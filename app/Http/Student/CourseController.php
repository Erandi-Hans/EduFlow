<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $student  = auth()->user()->student;
        $courses  = Course::with('teacher')->orderBy('course_name')->get();
        $enrolled = $student->courses()->pluck('courses.course_id')->all();

        return view('student.courses.index', compact('courses', 'enrolled'));
    }
}
