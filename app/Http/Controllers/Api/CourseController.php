<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Msg\ApiResponse;
use App\Http\Controllers\Msg\Pagination;
use App\Http\Controllers\Msg\Messages;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Student;



class CourseController extends Controller {

    // get the list of course
    public function index(Request $request){
        $page = $request->page ?? null;
        $perPage = $request->perPage ?? null;
        // $perPage = $request->query();

        $data = Course::where('status','active');
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

    // get the course by ID
    public function show($id){
        $course = Course::where('id',$id)
        ->where('status','active')
        ->first();
        if(!$course) return ApiResponse::error(Messages::$course_not_found, null, 404);
        return ApiResponse::success("Course details", $course);
    }

    // create a new course
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|unique:tbl_courses,title|max:255',
                'description' => 'required|string|max:1000',
                'status' => 'required|string|in:active,disabled'
            ], [
                'title.required' => 'Course title is required',
                'title.unique' => 'Course title must be unique',
                'description.required' => 'Course description is required',
                'status.required' => 'Course status is required',
            ]);

            $course = Course::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
            ]);

            return ApiResponse::success("Course created", $course, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors (e.g., duplicate entry)
            return ApiResponse::error("A database error occurred: " . $e->getMessage(), null, 500);
        } catch (\Exception $e) {
            // Handle other exceptions, including validation if not caught by validate()
            return ApiResponse::error("An error occurred: " . $e->getMessage(), null, 400);
        }
    }

    // update an existing course
    public function update(Request $request, $id)
    {
        try {
            $course = Course::where('id', $id)
                ->where('status', 'active')
                ->first();

            if (!$course) {
                return ApiResponse::error(Messages::$course_not_found, null, 404);
            }

            $validatedData = $request->validate([
                'title' => 'required|string|unique:tbl_courses,title,' . $course->id . '|max:255',
                'description' => 'required|string|max:1000',
                'status' => 'required|string|in:active,disabled'
            ], [
                'title.required' => 'Course title is required',
                'title.unique' => 'Course title must be unique',
                'description.required' => 'Course description is required',
                'status.required' => 'Course status is required',
            ]);

            $course->update([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
            ]);

            return ApiResponse::success("Course updated", $course, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return ApiResponse::error("A database error occurred: " . $e->getMessage(), null, 500);
        } catch (\Exception $e) {
            return ApiResponse::error("An error occurred: " . $e->getMessage(), null, 400);
        }
    }


    // destroy a course
    public function destroy($id){
        $course = Course::find($id);
        if(!$course) return ApiResponse::error(Messages::$course_not_found, null, 404);
        $course->update(['status'=>'disabled']);
        return ApiResponse::success("Course disabled");
    }
}
