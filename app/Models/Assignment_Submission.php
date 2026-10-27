<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = 'assignment_submissions';
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_file_path',
        'submitted_at',
        'marks',
        'status',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'marks' => 'decimal:2',
    ];

    // Relationship: Submission BELONGS TO Assignment
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'assignment_id');
    }

    // Relationship: Submission BELONGS TO Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
