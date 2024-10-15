<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // Relationships

    // Courses the user has created (teacher role)
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Courses the user has joined (student role)
    public function joinedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')
                    ->withTimestamps();
    }

    // User submissions
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Fillable fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // student or teacher
    ];

    // Hidden fields (e.g., password) to be excluded from serialization
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting certain attributes to specific types
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // JWTSubject interface methods

    /**
     * Get the identifier that will be stored in the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the user's primary key (usually the ID)
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role, // Add the user's role (teacher or student) to the JWT
        ];
    }
}
