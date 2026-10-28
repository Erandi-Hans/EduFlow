<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $primaryKey = 'assignment_id';
    protected $fillable = ['title', 'description', 'due_date', 'course_id'];

    protected function casts(): array
    {
        return ['due_date' => 'datetime'];
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id', 'assignment_id');
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast();
    }
}
