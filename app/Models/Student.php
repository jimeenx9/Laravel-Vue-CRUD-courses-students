<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'email', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
