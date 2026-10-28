<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

/**
 * Admin: courses CRUD, assign a teacher, enroll / un-enroll students.
 * Uses the query builder on the project tables (courses, teachers, students, users, course_enrollments).
 * Route parameters are read by position, so it works with the {Course} / {course} names in routes/web.php.
 */
class CourseController extends Controller
{
    public function index()
    {
        $courses = DB::table('courses')
            ->leftJoin('teachers', 'teachers.teacher_id', '=', 'courses.teacher_id')
            ->select('courses.*', DB::raw("concat(teachers.first_name, ' ', teachers.last_name) as teacher_name"))
            ->selectSub(
                fn($q) => $q->from('course_enrollments')
                    ->whereColumn('course_enrollments.course_id', 'courses.course_id')
                    ->selectRaw('count(*)'),
                'students_count'
            )
            ->orderBy('courses.course_code')
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create', ['course' => null, 'teachers' => $this->teachers()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['teacher_id'] = $data['teacher_id'] ?? null;

        if (Schema::hasColumn('courses', 'created_at')) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        DB::table('courses')->insert($data);

        return redirect()->route('admin.Course.index')->with('success', 'Course created.');
    }

    public function show($course)
    {
        $course = $this->find($course);

        $teacher = $course->teacher_id
            ? DB::table('teachers')->where('teacher_id', $course->teacher_id)->first()
            : null;

        $enrolled = DB::table('course_enrollments')
            ->join('students', 'students.student_id', '=', 'course_enrollments.student_id')
            ->join('users', 'users.user_id', '=', 'students.user_id')
            ->where('users.is_deleted', false)
            ->where('course_enrollments.course_id', $course->course_id)
            ->select('students.student_id', 'students.first_name', 'students.last_name', 'users.email')
            ->orderBy('students.first_name')
            ->get();

        $available = DB::table('students')
            ->join('users', 'users.user_id', '=', 'students.user_id')
            ->where('users.is_deleted', false)
            ->whereNotIn('students.student_id', $enrolled->pluck('student_id'))
            ->select('students.student_id', 'students.first_name', 'students.last_name')
            ->orderBy('students.first_name')
            ->get();

        return view('admin.courses.show', compact('course', 'teacher', 'enrolled', 'available'));
    }

    public function edit($course)
    {
        return view('admin.courses.edit', ['course' => $this->find($course), 'teachers' => $this->teachers()]);
    }

    public function update(Request $request, $course)
    {
        $course = $this->find($course);
        $data = $request->validate($this->rules($course->course_id));
        $data['teacher_id'] = $data['teacher_id'] ?? null;

        if (Schema::hasColumn('courses', 'updated_at')) {
            $data['updated_at'] = now();
        }

        DB::table('courses')->where('course_id', $course->course_id)->update($data);

        return redirect()->route('admin.Course.index')->with('success', 'Course updated.');
    }

    public function destroy($course)
    {
        $course = $this->find($course);

        try {
            DB::table('courses')->where('course_id', $course->course_id)->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['delete' => 'This course still has students or assignments, so it cannot be deleted.']);
        }

        return redirect()->route('admin.Course.index')->with('success', 'Course deleted.');
    }

    public function enroll(Request $request, $course)
    {
        $course = $this->find($course);
        $data = $request->validate(['student_id' => ['required', 'exists:students,student_id']]);

        $already = DB::table('course_enrollments')
            ->where('course_id', $course->course_id)->where('student_id', $data['student_id'])->exists();

        if (! $already) {
            $row = ['course_id' => $course->course_id, 'student_id' => $data['student_id']];

            if (Schema::hasColumn('course_enrollments', 'created_at')) {
                $row['created_at'] = now();
                $row['updated_at'] = now();
            }

            DB::table('course_enrollments')->insert($row);
        }

        return back()->with('success', 'Student enrolled.');
    }

    public function unenroll($course, $student)
    {
        $course = $this->find($course);

        DB::table('course_enrollments')
            ->where('course_id', $course->course_id)->where('student_id', $student)->delete();

        return back()->with('success', 'Student removed from the course.');
    }

    /* ------------------------------------------------------------- helpers */

    private function find($id)
    {
        return DB::table('courses')->where('course_id', $id)->first() ?? abort(404);
    }

    private function teachers()
    {
        return DB::table('teachers')
            ->join('users', 'users.user_id', '=', 'teachers.user_id')
            ->where('users.is_deleted', false)
            ->select('teachers.teacher_id', 'teachers.first_name', 'teachers.last_name')
            ->orderBy('teachers.first_name')
            ->get();
    }

    private function rules($ignoreId = null): array
    {
        $code = Rule::unique('courses', 'course_code');
        if ($ignoreId) {
            $code->ignore($ignoreId, 'course_id');
        }

        return [
            'course_name' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:20', $code],
            'teacher_id'  => ['nullable', 'exists:teachers,teacher_id'],
            'description' => ['nullable', 'string'],
        ];
    }
}
