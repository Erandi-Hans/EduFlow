<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->hasRole('Admin')) {
            $stats = [
                'Teachers' => Teacher::whereHas('user')->count(),
                'Students' => Student::whereHas('user')->count(),
                'Courses'  => Course::count(),
                'Assignments' => Assignment::count(),
            ];
        } elseif ($user->hasRole('Teacher')) {
            $teacher   = $user->teacher;
            $courseIds = $teacher->courses()->pluck('course_id');
            $stats = [
                'My courses'          => $courseIds->count(),
                'Assignments'         => Assignment::whereIn('course_id', $courseIds)->count(),
                'Waiting for grading' => AssignmentSubmission::whereNull('marks')
                    ->whereHas('assignment', fn($q) => $q->whereIn('course_id', $courseIds))->count(),
            ];
        } else {
            $student   = $user->student;
            $courseIds = $student->courses()->pluck('courses.course_id');
            $stats = [
                'My courses'         => $courseIds->count(),
                'Upcoming deadlines' => Assignment::whereIn('course_id', $courseIds)->where('due_date', '>', now())->count(),
                'Submitted'          => $student->submissions()->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}
