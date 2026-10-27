<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_name',
        'course_code',
        'description',
        'teacher_id',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    // Relationship: Course BELONGS TO Teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    // Relationship: Course BELONGS TO Admin/User (Creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // Relationship: Course HAS MANY Assignments
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id', 'course_id');
    }

    // Relationship: Course HAS MANY Enrolled Students (Pivot via course_enrollments)
    public function enrolledStudents()
    {
        return $this->belongsToMany(Student::class, 'course_enrollments', 'course_id', 'student_id')
            ->withPivot('enrolled_date', 'is_deleted')
            ->withTimestamps();
    }
}
