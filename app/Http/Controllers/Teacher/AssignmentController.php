<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with('course')->withCount('submissions')
            ->whereIn('course_id', $this->courseIds())
            ->latest('due_date')->paginate(10);

        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('teacher.assignments.create', ['assignment' => null, 'courses' => $this->courses()]);
    }

    public function store(Request $request)
    {
        Assignment::create($this->validated($request));

        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment created.');
    }

    public function edit(Assignment $assignment)
    {
        $this->authorizeOwner($assignment);

        return view('teacher.assignments.edit', ['assignment' => $assignment, 'courses' => $this->courses()]);
    }

    public function update(Request $request, Assignment $assignment)
    {
        $this->authorizeOwner($assignment);
        $assignment->update($this->validated($request));

        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment updated.');
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorizeOwner($assignment);
        $assignment->delete();

        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment deleted.');
    }

    private function courses()
    {
        return auth()->user()->teacher->courses()->orderBy('course_name')->get();
    }

    private function courseIds()
    {
        return auth()->user()->teacher->courses()->pluck('course_id');
    }

    private function authorizeOwner(Assignment $assignment): void
    {
        abort_unless($this->courseIds()->contains($assignment->course_id), 403);
    }

    private function validated(Request $request): array
    {
        $teacherId = auth()->user()->teacher->teacher_id;

        return $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:3000'],
            'due_date'    => ['required', 'date'],
            'course_id'   => ['required', Rule::exists('courses', 'course_id')->where('teacher_id', $teacherId)],
        ]);
    }
}
