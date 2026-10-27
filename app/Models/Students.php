<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'guardian_name',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    // Relationship: Student BELONGS TO User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship: Student HAS MANY Course Enrollments
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'student_id', 'student_id');
    }

    // Relationship: Student BELONGS TO MANY Courses (Pivot via course_enrollments)
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'student_id', 'course_id')
            ->withPivot('enrolled_date', 'is_deleted')
            ->withTimestamps();
    }

    // Relationship: Student HAS MANY Assignment Submissions
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id', 'student_id');
    }
}
