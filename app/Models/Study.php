<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model {
    protected $table = 'tbl_study';
    protected $fillable = ['student_id','course_id','teacher_id'];


    public function student() {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}

