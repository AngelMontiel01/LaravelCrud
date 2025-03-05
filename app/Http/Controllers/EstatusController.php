<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatusController extends Controller
{
    public function index()
    {
        $estatus = DB::select("EXEC SelEstatus");
        return response()->json($estatus);
    }
}
