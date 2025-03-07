<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
class PDFController extends Controller
{
    public function generarPdf()
    {
        $datos = DB::select("Exec ObtenerPedidos");
        $datos = json_decode(json_encode($datos), true);
        $pdf = PDF::loadView("pdf.pedidos", compact("datos"));
        return $pdf->download('pedidos.pdf');
    }

    public function generarPdfPedidosR()
    {
        $data = DB::select("Exec REPORTERECHAZADOS");
        $data = json_decode(json_encode($data), true);
        $pdf = PDF::loadView("pdf.PedidosR", compact("data"));
        return $pdf->download('PedidosR.pdf');
    }
}
