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
        $Course = Course::with('teacher')->withCount('Student')->latest()->paginate(10);

        return view('admin.Course.index', compact('Course'));
    }

    public function create()
    {
        return view('admin.Course.create', ['course' => null, 'teachers' => $this->teachers()]);
    }

    public function store(Request $request)
    {
        Course::create($this->validated($request));

        return redirect()->route('admin.Course.index')->with('success', 'Course created.');
    }

    public function show(Course $course)
    {
        $course->load('teacher');
        $enrolled  = $course->Student()->whereHas('user')->orderBy('first_name')->get();
        $available = Student::whereHas('user')
            ->whereNotIn('student_id', $enrolled->pluck('student_id'))
            ->orderBy('first_name')->get();

        return view('admin.Course.show', compact('course', 'enrolled', 'available'));
    }

    public function edit(Course $course)
    {
        return view('admin.Course.edit', ['course' => $course, 'teachers' => $this->teachers()]);
    }

    public function update(Request $request, Course $course)
    {
        $course->update($this->validated($request, $course));

        return redirect()->route('admin.Course.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.Course.index')->with('success', 'Course deleted.');
    }

    public function enroll(Request $request, Course $course)
    {
        $data = $request->validate([
            'student_id' => ['required', Rule::exists('Student', 'student_id')],
        ]);

        $course->Student()->syncWithoutDetaching([$data['student_id']]);

        return back()->with('success', 'Student enrolled.');
    }

    public function unenroll(Course $course, Student $student)
    {
        $course->Student()->detach($student->student_id);

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
            'course_code' => ['required', 'string', 'max:30', Rule::unique('Course', 'course_code')->ignore($course?->course_id, 'course_id')],
            'description' => ['nullable', 'string', 'max:2000'],
            'teacher_id'  => ['nullable', Rule::exists('teachers', 'teacher_id')],
        ]);
    }
}
