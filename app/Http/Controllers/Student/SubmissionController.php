<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    private const FILE_RULES = ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,txt,zip,png,jpg,jpeg'];

    public function store(Request $request, Assignment $assignment)
    {
        $student = auth::user()->student;

        abort_unless(
            $student->Course()->where('Course.course_id', $assignment->course_id)->exists(),
            403
        );

        if ($assignment->isOverdue()) {
            return back()->withErrors(['file' => 'The deadline for this assignment has passed.']);
        }

        if (AssignmentSubmission::where('assignment_id', $assignment->assignment_id)
            ->where('student_id', $student->student_id)->exists()
        ) {
            return back()->withErrors(['file' => 'You have already submitted this assignment. Use Replace file instead.']);
        }

        $request->validate(['file' => self::FILE_RULES]);

        AssignmentSubmission::create([
            'assignment_id'        => $assignment->assignment_id,
            'student_id'           => $student->student_id,
            'submission_file_path' => $request->file('file')->store('submissions', 'public'),
            'status'               => 'submitted',
        ]);

        return back()->with('success', 'Assignment submitted.');
    }

    public function update(Request $request, AssignmentSubmission $submission)
    {
        $this->authorizeOwner($submission);

        if ($submission->assignment->isOverdue()) {
            return back()->withErrors(['file' => 'The deadline has passed, so this submission can no longer be changed.']);
        }

        $request->validate(['file' => self::FILE_RULES]);

        Storage::disk('public')->delete($submission->submission_file_path);

        $submission->update([
            'submission_file_path' => $request->file('file')->store('submissions', 'public'),
            'marks'                => null,
            'status'               => 'submitted',
        ]);

        return back()->with('success', 'Submission updated.');
    }

    public function destroy(AssignmentSubmission $submission)
    {
        $this->authorizeOwner($submission);

        if ($submission->assignment->isOverdue()) {
            return back()->withErrors(['file' => 'The deadline has passed, so this submission can no longer be deleted.']);
        }

        Storage::disk('public')->delete($submission->submission_file_path);
        $submission->delete();

        return back()->with('success', 'Submission deleted.');
    }

    private function authorizeOwner(AssignmentSubmission $submission): void
    {
        abort_unless($submission->student_id === Auth::user()->student->student_id, 403);
    }
}
