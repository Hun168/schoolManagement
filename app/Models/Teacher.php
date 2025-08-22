<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model {
    protected $table = 'tbl_teachers';
    protected $fillable = ['name','email','status'];

    public function courses() {
        return $this->belongsToMany(Course::class, 'tbl_study', 'teacher_id', 'course_id')
                    ->withPivot('student_id');
    }
}

