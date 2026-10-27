<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'qualification',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    // Relationship: Teacher BELONGS TO User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship: Teacher HAS MANY Courses
    public function courses()
    {
        return $this->hasMany(Courses::class, 'teacher_id', 'teacher_id');
    }
}
