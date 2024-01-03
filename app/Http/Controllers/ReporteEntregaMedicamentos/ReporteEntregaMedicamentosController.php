<?php

namespace App\Http\Controllers\ReporteEntregaMedicamentos;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;

class ReporteEntregaMedicamentosController extends Controller
{
    public function reporteEntregaMedicamentos(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fechaInicio)->format('Y-m-d');
        $fechaFinal = Carbon::parse($request->fechaFin)->format('Y-m-d');
        $reporte = Collect(DB::table("BD_EntregaMedicamentosF")
        ->whereBetween('BD_EntregaMedicamentosF.Fecha_Reporte',[$fechaInicio,$fechaFinal])
        ->get());

        $array = json_decode($reporte, true);
        return (new FastExcel($array))->download('reporte.xls');

    }
}
