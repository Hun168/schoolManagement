<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    protected $table = 'tbl_courses';
    protected $fillable = ['title','description','status'];

    public function students() {
        return $this->belongsToMany(Student::class, 'tbl_study', 'course_id', 'student_id')
                    ->withPivot('teacher_id');
    }

    public function teachers() {
        return $this->belongsToMany(Teacher::class, 'tbl_study', 'course_id', 'teacher_id')
                    ->withPivot('student_id');
    }
}

