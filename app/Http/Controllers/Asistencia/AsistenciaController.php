<?php

namespace App\Http\Controllers\Asistencia;

use App\Modelos\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;

class AsistenciaController extends Controller
{
    public function saveAsistencia(Request $request)
    {
        Asistencia::create([
            'fecha'=> $request['fecha'],
            'ambito'=>'1',
            'finalidad'=>'4',
            'paciente_id'=>$request['id'],
            'user_id'=>auth()->user()->id,
            'cup_id'=>$request['cup_id']['id'],
            'tema'=>$request['tema']
        ]);
        return response()->json([
            'message'=> 'Asistencia guardada con exito!'
        ]);
    }

    public function reporteAsistencia(Request $request)
    {
        $asistencia = Collect(DB::select('exec dbo.reporte_asistencia_educativa ?,?', [$request->fecha_inicial,$request->fecha_final]));
        $array = json_decode($asistencia, true);
       return (new FastExcel($array))->download('asistencia.xls');

    }
}
