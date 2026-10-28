<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'course_id';
    protected $fillable = ['course_name', 'course_code', 'description', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id', 'course_id');
    }

    public function Student()
    {
        return $this->belongsToMany(Student::class, 'CourseEnrollments', 'course_id', 'student_id', 'course_id', 'student_id')
            ->withTimestamps();
    }
}
