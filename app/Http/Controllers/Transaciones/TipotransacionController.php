<?php

namespace App\Http\Controllers\Transaciones;

use App\Http\Controllers\Controller;
use App\Modelos\Tipotransacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipotransacionController extends Controller
{

    public function all()
    {
        $tipotransacion = Tipotransacion::select(['Tipotransacions.*', 'Tipos.Nombre as TipoNombre', 'Transacions.Nombre as TransacionNombre'])
            ->join('Tipos', 'Tipotransacions.Tipo_id', 'Tipos.id')
            ->join('Transacions', 'Tipotransacions.Transacion_id', 'Transacions.id')
            ->where('Transacions.Estado_id', 1)
            ->get();
        return response()->json($tipotransacion, 200);
    }

    public function solicitud()
    {
        $tipotransacion = Tipotransacion::select(['Tipotransacions.*', 'Tipos.Nombre as TipoNombre', 'Transacions.Nombre as TransacionNombre'])
            ->join('Tipos', 'Tipotransacions.Tipo_id', 'Tipos.id')
            ->join('Transacions', 'Tipotransacions.Transacion_id', 'Transacions.id')
            ->where('Transacions.Estado_id', 1)
            ->whereIn('Tipotransacions.id', [1, 2, 6, 7, 9])
            ->get();
        return response()->json($tipotransacion, 200);
    }

    public function auditoria()
    {
        $tipotransacion = Tipotransacion::select(['Tipotransacions.*', 'Tipos.Nombre as TipoNombre', 'Transacions.Nombre as TransacionNombre'])
            ->join('Tipos', 'Tipotransacions.Tipo_id', 'Tipos.id')
            ->join('Transacions', 'Tipotransacions.Transacion_id', 'Transacions.id')
            ->where('Transacions.Estado_id', 1)
            ->whereIn('Tipotransacions.id', [2])
            ->get();
        return response()->json($tipotransacion, 200);
    }

    public function movimiento()
    {
        $tipotransacion = Tipotransacion::select(['Tipotransacions.*', 'Tipos.Nombre as TipoNombre', 'Transacions.Nombre as TransacionNombre'])
            ->join('Tipos', 'Tipotransacions.Tipo_id', 'Tipos.id')
            ->join('Transacions', 'Tipotransacions.Transacion_id', 'Transacions.id')
            ->where('Transacions.Estado_id', 1)
            ->whereIn('Tipotransacions.id', [2, 4, 6, 7])
            ->get();
        return response()->json($tipotransacion, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Transacion_id' => 'required',
            'Tipo_id'       => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input          = $request->all();
        $tipotransacion = Tipotransacion::create($input);
        return response()->json([
            'message' => 'tipotransacion creado con exito!',
        ], 201);
    }

    public function update(Request $request, Tipotransacion $tipotransacion)
    {
        $tipotransacion->update($request->all());
        return response()->json([
            'message' => 'Tipotransacion actualizado con exito!',
        ]);
    }
}
