<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole('Admin')   => $this->admin($user),
            $user->hasRole('Teacher') => $this->teacher($user),
            default                   => $this->student($user),
        };
    }

    private function greeting(): string
    {
        $hour = now()->hour;

        return $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    }

    /* ------------------------------------------------------------------ ADMIN */
    private function admin(User $user)
    {
        $students    = Student::whereHas('user')->count();
        $teachers    = Teacher::whereHas('user')->count();
        $admins      = User::whereHas('role', fn($q) => $q->where('role_name', 'Admin'))->count();
        $newMembers  = User::where('created_at', '>=', now()->subDays(7))->count();
        $needTeacher = Course::whereNull('teacher_id')->count();

        $text = "You have {$newMembers} new " . Str::plural('member', $newMembers)
            . " this week and {$needTeacher} " . Str::plural('course', $needTeacher) . ' needing a teacher.';

        return view('dashboard', [
            'role'   => 'Admin',
            'banner' => [
                'chip'  => 'Administrator',
                'title' => $this->greeting() . ', ' . Str::title($user->displayName()),
                'text'  => $text,
                'label' => 'Manage users',
                'route' => 'admin.users.index',
            ],
            'stats' => [
                ['Students', $students, 'users'],
                ['Teachers', $teachers, 'users'],
                ['Courses', Course::count(), 'book'],
                ['Assignments', Assignment::count(), 'file'],
            ],
            'recentMembers' => User::with(['role', 'teacher', 'student'])->latest()->take(5)->get(),
            'membership'    => ['Students' => $students, 'Teachers' => $teachers, 'Admins' => $admins],
        ]);
    }

    /* ---------------------------------------------------------------- TEACHER */
    private function teacher(User $user)
    {
        $teacher   = $user->teacher;
        $courseIds = $teacher ? $teacher->Course()->pluck('course_id') : collect();

        $studentCount = $courseIds->isEmpty() ? 0
            : DB::table('CourseEnrollments')->whereIn('course_id', $courseIds)->distinct()->count('student_id');

        // Submissions that still have no marks, for this teacher's courses
        $toGradeQuery = AssignmentSubmission::whereNull('marks')
            ->whereHas('assignment', fn($q) => $q->whereIn('course_id', $courseIds));

        $toGrade = (clone $toGradeQuery)->count();
        $latest  = (clone $toGradeQuery)->latest('submission_id')->take(3)->get();

        $studentsById = Student::whereIn('student_id', $latest->pluck('student_id'))->get()->keyBy('student_id');
        $titles       = Assignment::whereIn('assignment_id', $latest->pluck('assignment_id'))->pluck('title', 'assignment_id');

        $submissions = $latest->map(fn($s) => [
            'student'       => $studentsById->get($s->student_id)?->full_name ?? 'Student',
            'assignment'    => $titles[$s->assignment_id] ?? 'Assignment',
            'assignment_id' => $s->assignment_id,
        ]);

        return view('dashboard', [
            'role'   => 'Teacher',
            'banner' => [
                'chip'  => 'Teacher',
                'title' => $this->greeting() . ', ' . Str::title($user->displayName()),
                'text'  => $toGrade === 0
                    ? 'No submissions are waiting to be graded.'
                    : "{$toGrade} " . Str::plural('submission', $toGrade) . ($toGrade === 1 ? ' is' : ' are') . ' waiting to be graded.',
                'label' => 'Review submissions',
                'route' => 'teacher.assignments.index',
            ],
            'stats' => [
                ['My courses', $courseIds->count(), 'book'],
                ['Students', $studentCount, 'users'],
                ['Assignments', Assignment::whereIn('course_id', $courseIds)->count(), 'file'],
                ['To grade', $toGrade, 'check'],
            ],
            'submissions' => $submissions,
        ]);
    }

    /* ---------------------------------------------------------------- STUDENT */
    private function student(User $user)
    {
        $student = $user->student;
        $courses = $student ? $student->Course()->get() : collect();
        $courseIds = $courses->pluck('course_id');

        $assignments = Assignment::whereIn('course_id', $courseIds)->orderBy('due_date')->get();
        $mine        = $student ? $student->submissions()->get()->keyBy('assignment_id') : collect();

        $pending   = $assignments->filter(fn($a) => ! $mine->has($a->assignment_id));
        $submitted = $mine->filter(fn($s) => is_null($s->marks))->count();   // waiting for the teacher
        $graded    = $mine->filter(fn($s) => ! is_null($s->marks))->count();

        $next = $pending->first(fn($a) => Carbon::parse($a->due_date)->endOfDay()->isFuture());
        $firstName = $student?->first_name ?? Str::title($user->displayName());
        $count = $pending->count();

        $text = $next
            ? "Your next deadline is the {$next->title} on " . Carbon::parse($next->due_date)->format('M j') . '.'
            : 'You have no upcoming deadlines.';

        $codes = $courses->pluck('course_code', 'course_id');

        // Upcoming first, then the most recent past ones, max 4
        $upcoming = $assignments
            ->sortBy(fn($a) => (Carbon::parse($a->due_date)->isPast() ? '1' : '0') . Carbon::parse($a->due_date)->format('Y-m-d'))
            ->take(4)
            ->map(function ($a) use ($mine, $codes) {
                $sub = $mine->get($a->assignment_id);

                return [
                    'title'  => $a->title,
                    'code'   => $codes[$a->course_id] ?? '',
                    'due'    => Carbon::parse($a->due_date)->format('M d'),
                    'status' => $sub ? (is_null($sub->marks) ? 'Submitted' : 'Graded') : 'Pending',
                ];
            })->values();

        $teachers = Teacher::whereIn('teacher_id', $courses->pluck('teacher_id')->filter())->get()->keyBy('teacher_id');
        $myCourses = $courses->take(5)->map(fn($c) => [
            'name'    => $c->course_name,
            'teacher' => $teachers->get($c->teacher_id)?->full_name ?? '',
        ])->values();

        return view('dashboard', [
            'role'   => 'Student',
            'banner' => [
                'chip'  => 'Student',
                'title' => $count === 0
                    ? "Hi {$firstName}, you are all caught up"
                    : "Hi {$firstName}, {$count} " . Str::plural('assignment', $count) . ($count === 1 ? ' is' : ' are') . ' waiting',
                'text'  => $text,
                'label' => 'View assignments',
                'route' => 'student.assignments.index',
            ],
            'stats' => [
                ['Enrolled courses', $courses->count(), 'book'],
                ['Pending', $count, 'file'],
                ['Submitted', $submitted, 'upload'],
                ['Graded', $graded, 'check'],
            ],
            'upcoming'  => $upcoming,
            'myCourses' => $myCourses,
        ]);
    }
}
