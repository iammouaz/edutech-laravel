<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'course_id', 'due_date'];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function submissions() {
        return $this->hasMany(Submission::class);
    }

}
