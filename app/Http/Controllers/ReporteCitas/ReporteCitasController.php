<?php

namespace App\Http\Controllers\ReporteCitas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ReporteCitasController extends Controller
{
    public function reporteCitas(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fechaInicio)->format('Y-m-d');
        $fechaFinal = Carbon::parse($request->fechaFin)->format('Y-m-d');

        $reporte = Collect(DB::table("Reporte_Citas")
        ->whereBetween('Reporte_Citas.Fecha_Asignacion_Cita',[$fechaInicio,$fechaFinal])
        ->get());
        // dd($reporte);
        $array = json_decode($reporte, true);
        return (new FastExcel($array))->download('reporte.xls');

    }
}
