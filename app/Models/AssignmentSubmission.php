<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmission extends Model
{
    protected $primaryKey = 'submission_id';
    protected $table = 'assignmentsubmissions';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_file_path',
        'marks',
        'status'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function fileUrl(): string
    {
        return Storage::disk('public')->url($this->submission_file_path);
    }
}
