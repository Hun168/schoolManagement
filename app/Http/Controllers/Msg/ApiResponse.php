<?php

namespace App\Http\Controllers\Msg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiResponse extends Controller
{
    // message succee
    public static function success($message = "Success", $data = null, $status = 200){
        return response()->json([
            'success'=>true,
            'message'=>$message,
            'result'=>$data
        ], $status);
    }

    // message error
    public static function error($message = "Error", $data = null, $status = 400){
        return response()->json([
            'success'=>false,
            'message'=>$message,
            'result'=>$data
        ], $status);
    }

}
