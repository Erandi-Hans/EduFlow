<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // The migration creates the table as "Courses" (same name the Student model uses: 'Courses.course_id')
    protected $table = 'Courses';

    protected $primaryKey = 'course_id';

    protected $fillable = ['course_name', 'course_code', 'description', 'teacher_id'];

    /** The teacher who runs this course (can be null). */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'teacher_id');
    }

    /** Students enrolled in this course (pivot table = CourseEnrollments, same as Student::Course()). */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'CourseEnrollments', 'course_id', 'student_id', 'course_id', 'student_id')
            ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id', 'course_id');
    }

    /* Aliases, in case other parts of the project call these (capital letter style like Student::Course()) */
    public function Student()
    {
        return $this->students();
    }

    public function Assignment()
    {
        return $this->assignments();
    }
}
