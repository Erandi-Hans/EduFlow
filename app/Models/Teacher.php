<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $primaryKey = 'teacher_id';
    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone_number', 'qualification'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function Course()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'teacher_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function displayName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
