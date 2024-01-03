<?php

namespace App\Http\Controllers\Estadistica;

use App\Http\Controllers\Controller;
use App\Modelos\Estadistica;
use Illuminate\Http\Request;

class EstadisticaController extends Controller
{
    public function all()
    {
        $prueba = Estadistica::select('estadisticas.id','estadisticas.titulo','estadisticas.imagen','estadisticas.inframe', 'p.name as permiso')
        ->join('permissions as p', 'estadisticas.permission_id', 'p.id')
        ->where('estadisticas.estado_id', 1)
        ->get();
        return response()->json($prueba, 200);
    }

    public function crear(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = time().$file->getClientOriginalName();
            $path = '../storage/app/public/estadistica/';
            $pathDB = '/storage/estadistica/'.$file_name;
            $file->move($path, $file_name);
            Estadistica::create([
                'titulo' => $request->titulo,
                'imagen' => $pathDB,
                'inframe' => $request->inframe,
                'permission_id' => $request->permiso
            ]);
        }
        return response()->json([
            'message' => 'Estadistica creada con exito'
        ]);
    }
}
