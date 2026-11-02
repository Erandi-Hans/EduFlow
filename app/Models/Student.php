<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    protected $fillable = ['user_id', 'first_name', 'last_name', 'date_of_birth', 'guardian_name'];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function Course()
    {
        return $this->belongsToMany(Course::class, 'CourseEnrollments', 'student_id', 'course_id', 'student_id', 'course_id')
            ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id', 'student_id');
    }

    public function getFullNameAttribute(): string //Accessor 
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

//How to identify accessor
//-accesser name start with "get" key word
//-chngebale column name should be camel case ex-: firstname FirstName
//-end of the word we have to use Attribute word
// ex- getFullNameAttrribute()  
// accessor help to us to show the data from database , according to our wish(format) 
