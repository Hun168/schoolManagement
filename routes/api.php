<?php

use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\StudyController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Support\Facades\Route;



// Route CRUD student
Route::prefix('students')->group(function(){
    Route::get('/', [StudentController::class,'index']);
    Route::get('/{id}', [StudentController::class,'show']);
    Route::post('/', [StudentController::class,'store']);
    Route::put('/{id}', [StudentController::class,'update']);
    Route::delete('/{id}', [StudentController::class,'destroy']);
});

// Route CRUD course
Route::prefix('courses')->group(function(){
    Route::get('/', [CourseController::class,'index']);
    Route::get('/{id}', [CourseController::class,'show']);
    Route::post('/', [CourseController::class,'store']);
    Route::put('/{id}', [CourseController::class,'update']);
    Route::delete('/{id}', [CourseController::class,'destroy']);
});



// Route CRUD teacher
Route::prefix('teachers')->group(function(){
    Route::get('/', [TeacherController::class,'index']);
    Route::get('/{id}', [TeacherController::class,'show']);
    Route::post('/', [TeacherController::class,'store']);
    Route::put('/{id}', [TeacherController::class,'update']);
    Route::delete('/{id}', [TeacherController::class,'destroy']);
});

// Route CRUD study


Route::prefix('studies')->group(function () {
    Route::get('/', [StudyController::class,'index']);
    Route::get('/{id}', [StudyController::class,'show']);
    Route::post('/', [StudyController::class,'store']);   // <= important
    Route::put('/{id}', [StudyController::class,'update']);
    Route::delete('/{id}', [StudyController::class,'destroy']);
});
