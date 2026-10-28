<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $table = 'CourseEnrollments';
    protected $primaryKey = 'enrollment_id';

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_date',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    protected $casts = [
        'enrolled_date' => 'datetime',
    ];

    // Relationship: Enrollment BELONGS TO Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    // Relationship: Enrollment BELONGS TO Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
