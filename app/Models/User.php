<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = ['username', 'first_name', 'last_name', 'email', 'password', 'role_id', 'is_deleted', 'profile_photo',];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'   => 'hashed',
            'is_deleted' => 'boolean',
        ];
    }

    /**
     * Soft delete: removed users are hidden everywhere (and cannot log in,
     * because the auth provider uses this model too).
     */
    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $query) {
            $query->where('users.is_deleted', false);
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id', 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->role->role_name === $role;
    }

    public function displayName(): string
    {
        $profile = $this->teacher ?? $this->student;

        return $profile ? $profile->full_name : $this->username;
    }
}
