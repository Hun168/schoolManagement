<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Msg\ApiResponse;
use App\Http\Controllers\Msg\Pagination;
use App\Http\Controllers\Msg\Messages;
use Illuminate\Http\Request;
use App\Models\Student;



class StudentController extends Controller {

    // get the list of student
    public function index(Request $request){
        $page = $request->page ?? null;
        $perPage = $request->perPage ?? null;
        // $perPage = $request->query();

        $data = Student::where('status','active');
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

        // $students = Pagination::paginate(Student::where('status','active'), $perPage);
        // return ApiResponse::success("List of students", $students);
    }

    // get the student by ID
    public function show($id){
        $student = Student::where('id',$id)
        ->where('status','active')
        ->first();
        if(!$student) return ApiResponse::error(Messages::$student_not_found, null, 404);
        return ApiResponse::success("Student details", $student);
    }

    // create a new student
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_students,email'
            ], [
                'name.required' => 'Student name is required',
                'name.max' => 'Student name must not exceed 255 characters',
                'email.required' => 'Student email is required',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
            ]);

            $student = Student::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            return ApiResponse::success("Student created", $student, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            return ApiResponse::error("A database error occurred: " . $e->getMessage(), null, 500);
        } catch (\Exception $e) {
            return ApiResponse::error("An error occurred: " . $e->getMessage(), null, 400);
        }
    }

    // update an existing student
    public function update(Request $request, $id){
        $student = Student::where('id',$id)
        ->where('status','active')
        ->first();
        if(!$student) 
            return ApiResponse::error(Messages::$student_not_found, null, 404);
        $student->update($request->all());
        return ApiResponse::success("Student updated", $student);
    }


    // destroy a student
    public function destroy($id){
        $student = Student::find($id);
        if(!$student) return ApiResponse::error(Messages::$student_not_found, null, 404);
        $student->update(['status'=>'disabled']);
        return ApiResponse::success("Student disabled");
    }
}
