<?php

namespace App\Http\Controllers\AdjuntoAnexoSaludOcupacional;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Modelos\AdjuntoAnexoSaludOcupacional;
use Illuminate\Support\Facades\Storage;

class AdjuntoAnexoSaludOcupacionalController extends Controller
{
    public function guardar(Request $request){
        $paths = [];
        $ruta = 'adjuntos_medicinalaboral';
        $fecha = Carbon::now();

        if (!$request->hasFile('adjuntos')) {
            return response()->json(false);
        }

        $files = $request->file('adjuntos');
        foreach ($files as $file) {
            array_push($paths, [
                'nombre' => $file->getClientOriginalName(),
                'ruta' =>Storage::disk('ftp')->put($ruta, $file),
                'cedula_paciente' => $request->cedula_paciente,
                'tipo_anexo_id' => $request->tipo_anexo_id,
                'fecha_proceso' => $request->fecha_proceso,
                'created_at' => $fecha
            ]);
        }
        AdjuntoAnexoSaludOcupacional::insert($paths);
        return response()->json($paths, Response::HTTP_CREATED);
    }
}
