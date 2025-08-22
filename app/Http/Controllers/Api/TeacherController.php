<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Msg\ApiResponse;
use App\Http\Controllers\Msg\Pagination;
use App\Http\Controllers\Msg\Messages;
use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller {

    // get the list of teacher
    public function index(Request $request){
        $page = $request->page ?? null;
        $perPage = $request->perPage ?? null;
        // $perPage = $request->query();

        $data = Teacher::where('status','active');
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

    // get the teacher by ID
    public function show($id){
        $teacher = Teacher::where('id',$id)
        ->where('status','active')
        ->first();
        if(!$teacher) return ApiResponse::error(Messages::$teacher_not_found, null, 404);
        return ApiResponse::success("Teacher details", $teacher);
    }

    // create a new teacher
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:tbl_teachers,email'
        ]);
        $teacher = Teacher::create($request->all());
        return ApiResponse::success("Teacher created", $teacher, 201);
    }

    // update an existing teacher
    public function update(Request $request, $id){
        $teacher = Teacher::where('id',$id)
        ->where('status','active')
        ->first();
        if(!$teacher) 
            return ApiResponse::error(Messages::$teacher_not_found, null, 404);
        $teacher->update($request->all());
        return ApiResponse::success("Teacher updated", $teacher);
    }


    // destroy a teacher
    public function destroy($id){
        $teacher = Teacher::find($id);
        if(!$teacher) return ApiResponse::error(Messages::$teacher_not_found, null, 404);
        $teacher->update(['status'=>'disabled']);
        return ApiResponse::success("Teacher disabled");
    }
}
