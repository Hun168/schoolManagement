<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Msg\ApiResponse;
use App\Http\Controllers\Msg\Messages;
use App\Http\Controllers\Msg\Pagination;
use Illuminate\Http\Request;
use App\Models\Study;
use App\Models\Student;
use App\Models\Course;
use App\Models\Teacher;


class StudyController extends Controller {

    // Get all study relations
    public function index(Request $request){
        $page = $request->page ?? null;
        $perPage = $request->perPage ?? null;
        // $perPage = $request->query();

        $data = Study::with(['student','course','teacher'])->where('status','active');
        if($perPage) {
            $data = $data->paginate($perPage);
        } else {
            $data = $data->get();
        }

        return response()->json([
            'status' => 200,
            'page' => $page,
            'perPage' => $perPage,
            'data' => $data->items()
        ]);
    }

    // Get study relation by ID
    public function show($id){
        $study = Study::with(['student','course','teacher'])->find($id);
        if(!$study) return ApiResponse::error("Study relation not found", null, 404);
        return ApiResponse::success("Study relation details", $study);
    }

    // Assign Student -> Course -> Teacher
    public function store(Request $request){
        $request->validate([
            'student_id'=>'required|exists:tbl_students,id',
            'course_id'=>'required|exists:tbl_courses,id',
            'teacher_id'=>'required|exists:tbl_teachers,id',
        ]);

        // Ensure only active students/courses/teachers can be assigned
        $student = Student::where('id',$request->student_id)->where('status','active')->first();
        $course  = Course::where('id',$request->course_id)->where('status','active')->first();
        $teacher = Teacher::where('id',$request->teacher_id)->where('status','active')->first();

        if(!$student) return ApiResponse::error(Messages::$student_not_found, null, 404);
        if(!$course) return ApiResponse::error(Messages::$course_not_found, null, 404);
        if(!$teacher) return ApiResponse::error(Messages::$teacher_not_found, null, 404);

        $study = Study::create($request->all());
        return ApiResponse::success("Study relation created", $study, 201);
    }

    // Update relation
    public function update(Request $request, $id){
        $study = Study::find($id);
        if(!$study) return ApiResponse::error("Study relation not found", null, 404);

        $request->validate([
            'student_id'=>'required|exists:tbl_students,id',
            'course_id'=>'required|exists:tbl_courses,id',
            'teacher_id'=>'required|exists:tbl_teachers,id',
        ]);

        $student = Student::where('id',$request->student_id)->where('status','active')->first();
        $course  = Course::where('id',$request->course_id)->where('status','active')->first();
        $teacher = Teacher::where('id',$request->teacher_id)->where('status','active')->first();

        if(!$student) return ApiResponse::error(Messages::$student_not_found, null, 404);
        if(!$course) return ApiResponse::error(Messages::$course_not_found, null, 404);
        if(!$teacher) return ApiResponse::error(Messages::$teacher_not_found, null, 404);

        $study->update($request->all());
        return ApiResponse::success("Study relation updated", $study);
    }

    // Delete relation (Hard delete because it's only pivot)
    public function destroy($id){
        $study = Study::find($id);
        if(!$study) return ApiResponse::error("Study relation not found", null, 404);
        $study->delete();
        return ApiResponse::success("Study relation deleted");
    }
}

