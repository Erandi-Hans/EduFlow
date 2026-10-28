<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $student   = Auth::user()->student;
        $courseIds = $student->Course()->pluck('Courses.course_id');

        $assignments = Assignment::with('course')
            ->whereIn('course_id', $courseIds)
            ->orderBy('due_date')->get();

        $submissions = AssignmentSubmission::where('student_id', $student->student_id)
            ->get()->keyBy('assignment_id');

        return view('student.assignments.index', compact('assignments', 'submissions'));
    }
}
