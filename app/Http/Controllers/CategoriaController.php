<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $cate = DB::select("EXEC selCategoria");
        return response()->json($cate);
    }
}
