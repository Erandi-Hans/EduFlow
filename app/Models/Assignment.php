<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';
    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'course_id',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // Relationship: Assignment BELONGS TO Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    // Relationship: Assignment HAS MANY Submissions
    public function submissions()
    {
        return $this->hasMany(Assignment_Submission::class, 'assignment_id', 'assignment_id');
    }
}
