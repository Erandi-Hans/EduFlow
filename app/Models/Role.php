<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Table hesaru mattu primary key matru custom agi define maduvudu
    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    // Mass assignable fields
    protected $fillable = [
        'role_name',
    ];

    /**
     * Relationship: A role can belong to many users.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
