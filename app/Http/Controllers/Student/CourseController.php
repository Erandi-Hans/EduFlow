<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Auth;

class CourseController extends Controller
{
    public function index()
    {
        $student  = Auth::user()->student;
        $Course  = Course::with('teacher')->orderBy('course_name')->get();
        $enrolled = $student->Course()->pluck('Course.course_id')->all();

        return view('student.Course.index', compact('Course', 'enrolled'));
    }
}
