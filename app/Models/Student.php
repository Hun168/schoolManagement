<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $table = 'tbl_students';
    public $timestamps = false; // Assuming timestamps are not used
    protected $fillable = ['name','email','status'];

    public function courses() {
        return $this->belongsToMany(Course::class, 'tbl_study', 'student_id', 'course_id')
                    ->withPivot('teacher_id');
    }
}

