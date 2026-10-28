<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teacher')->withCount('students')->latest()->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create', ['course' => null, 'teachers' => $this->teachers()]);
    }

    public function store(Request $request)
    {
        Course::create($this->validated($request));

        return redirect()->route('admin.courses.index')->with('success', 'Course created.');
    }

    public function show(Course $course)
    {
        $course->load('teacher');
        $enrolled  = $course->students()->whereHas('user')->orderBy('first_name')->get();
        $available = Student::whereHas('user')
            ->whereNotIn('student_id', $enrolled->pluck('student_id'))
            ->orderBy('first_name')->get();

        return view('admin.courses.show', compact('course', 'enrolled', 'available'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', ['course' => $course, 'teachers' => $this->teachers()]);
    }

    public function update(Request $request, Course $course)
    {
        $course->update($this->validated($request, $course));

        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }

    public function enroll(Request $request, Course $course)
    {
        $data = $request->validate([
            'student_id' => ['required', Rule::exists('students', 'student_id')],
        ]);

        $course->students()->syncWithoutDetaching([$data['student_id']]);

        return back()->with('success', 'Student enrolled.');
    }

    public function unenroll(Course $course, Student $student)
    {
        $course->students()->detach($student->student_id);

        return back()->with('success', 'Student removed from the course.');
    }

    private function teachers()
    {
        return Teacher::whereHas('user')->orderBy('first_name')->get();
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'course_name' => ['required', 'string', 'max:120'],
            'course_code' => ['required', 'string', 'max:30', Rule::unique('courses', 'course_code')->ignore($course?->course_id, 'course_id')],
            'description' => ['nullable', 'string', 'max:2000'],
            'teacher_id'  => ['nullable', Rule::exists('teachers', 'teacher_id')],
        ]);
    }
}
