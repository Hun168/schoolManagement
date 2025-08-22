<?php

namespace App\Http\Controllers\Msg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Messages extends Controller
{
    // student messages
    public static $student_not_found = "Student not found or disabled!";

    // course messages
    public static $course_not_found  = "Course not found or disabled!";

    // teacher messages
    public static $teacher_not_found = "Teacher not found or disabled!";
}
