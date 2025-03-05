<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
    {
        $ciudad = DB::select("EXEC selCiudad");
        return response()->json($ciudad);
    }
}
