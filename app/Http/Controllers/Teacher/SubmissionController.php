<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Auth;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Assignment $assignment)
    {
        $this->authorizeOwner($assignment);

        $assignment->load('course');
        $submissions = $assignment->submissions()->with('student')->latest('updated_at')->get();

        return view('teacher.submissions.index', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, AssignmentSubmission $submission)
    {
        $this->authorizeOwner($submission->assignment);

        $data = $request->validate([
            'marks' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $submission->update(['marks' => $data['marks'], 'status' => 'graded']);

        return back()->with('success', 'Marks saved.');
    }

    private function authorizeOwner(Assignment $assignment): void
    {
        abort_unless(
            $assignment->course->teacher_id === Auth::user()->teacher->teacher_id,
            403
        );
    }
}
