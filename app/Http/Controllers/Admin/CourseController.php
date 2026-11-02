<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Admin: courses CRUD, assign a teacher, enroll / un-enroll students.
 * Everything goes through the Eloquent models, so the real table names ("Courses", "CourseEnrollments")
 * come from the models and nothing is hard-coded here.
 * Route parameters are read by position, so it works with {Course} / {course} in routes/web.php.
 */
class CourseController extends Controller
{
    /**
     * Show all courses as cards.
     * The optional "search" in the URL filters by course name, course code or teacher name.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $courses = Course::with('teacher')
            ->withCount(['students' => fn($q) => $q->whereHas('user')])   // ignore removed (soft deleted) students
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';

                $q->where(function ($q) use ($like) {
                    $q->where('course_name', 'like', $like)
                        ->orWhere('course_code', 'like', $like)
                        ->orWhereHas('teacher', fn($t) => $t->where('first_name', 'like', $like)->orWhere('last_name', 'like', $like));
                });
            })
            ->orderBy('course_code')
            ->get();

        return view('admin.courses.index', compact('courses', 'search'));
    }

    public function create()
    {
        return view('admin.courses.create', ['course' => null, 'teachers' => $this->teachers()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        Course::create($data);

        return redirect()->route('admin.Course.index')->with('success', 'Course created.');
    }

    public function show($id)
    {
        $course = Course::with('teacher')->findOrFail($id);

        // pivot->created_at = the date the student was enrolled
        $enrolled = $course->students()
            ->whereHas('user')
            ->with('user')
            ->orderBy('first_name')
            ->get();

        $available = Student::whereHas('user')
            ->whereNotIn('student_id', $enrolled->pluck('student_id'))
            ->orderBy('first_name')
            ->get();

        $assignments = $course->assignments()->orderBy('due_date')->get();

        return view('admin.courses.show', compact('course', 'enrolled', 'available', 'assignments'));
    }

    public function edit($id)
    {
        $course = Course::with('teacher')
            ->withCount(['students' => fn($q) => $q->whereHas('user')])
            ->findOrFail($id);

        return view('admin.courses.edit', ['course' => $course, 'teachers' => $this->teachers()]);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update($this->validated($request, $course->course_id));

        return redirect()->route('admin.Course.index')->with('success', 'Course updated.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->students()->exists() || $course->assignments()->exists()) {
            return back()->withErrors(['delete' => 'This course still has students or assignments, so it cannot be deleted.']);
        }

        try {
            $course->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['delete' => 'This course is still in use, so it cannot be deleted.']);
        }

        return redirect()->route('admin.Course.index')->with('success', 'Course deleted.');
    }

    /* ---------------------------------------------------- enroll / un-enroll */

    public function enroll(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $data = $request->validate([
            'student_id' => ['required', Rule::exists(Student::class, 'student_id')],
        ]);

        // adds the row only if it is not there yet
        $course->students()->syncWithoutDetaching([$data['student_id']]);

        return back()->with('success', 'Student enrolled.');
    }

    public function unenroll($id, $student)
    {
        $course = Course::findOrFail($id);

        $course->students()->detach($student);

        return back()->with('success', 'Student removed from the course.');
    }

    /* ------------------------------------------------------------- helpers */

    private function teachers()
    {
        return Teacher::whereHas('user')->orderBy('first_name')->get();
    }

    private function validated(Request $request, $ignoreId = null): array
    {
        $code = Rule::unique(Course::class, 'course_code');
        if ($ignoreId) {
            $code->ignore($ignoreId, 'course_id');
        }

        $data = $request->validate([
            'course_name' => ['required', 'string', 'max:120'],
            'course_code' => ['required', 'string', 'max:30', $code],
            'teacher_id'  => ['nullable', Rule::exists(Teacher::class, 'teacher_id')],
            'description' => ['nullable', 'string'],
        ]);

        $data['course_code'] = strtoupper(trim($data['course_code']));
        $data['teacher_id']  = $data['teacher_id'] ?? null;

        return $data;
    }
}
