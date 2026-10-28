<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $stats = [];

        if ($user->hasRole('Admin')) {
            $stats = [
                'Teachers' => Teacher::whereHas('user')->count(),
                'Student' => Student::whereHas('user')->count(),
                'Course'  => Course::count(),
                'Assignments' => Assignment::count(),
            ];
        } elseif ($user->hasRole('Teacher')) {
            $teacher   = $user->teacher;
            $courseIds = $teacher->Course()->pluck('course_id');
            $stats = [
                'My Course'          => $courseIds->count(),
                'Assignments'         => Assignment::whereIn('course_id', $courseIds)->count(),
                'Waiting for grading' => AssignmentSubmission::whereNull('marks')
                    ->whereHas('assignment', fn($q) => $q->whereIn('course_id', $courseIds))->count(),
            ];
        } else {
            $student   = $user->student;
            $courseIds = $student->Course()->pluck('Courses.course_id');
            $stats = [
                'My Course'         => $courseIds->count(),
                'Upcoming deadlines' => Assignment::whereIn('course_id', $courseIds)->where('due_date', '>', now())->count(),
                'Submitted'          => $student->submissions()->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}
