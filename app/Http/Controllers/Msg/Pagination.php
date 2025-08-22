<?php

namespace App\Http\Controllers\Msg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Pagination extends Controller
{
    // paginate the result
    public static function paginate($query, $perPage = 5){
        return $query->paginate($perPage);
    }
}
