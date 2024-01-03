<?php

namespace App\Http\Controllers\Codigoglosas;

use App\Modelos\Tipo;
use Illuminate\Http\Request;
use App\Modelos\Codigo_glosas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CodigoglosaController extends Controller
{
    public function all()
    {
        $codigoglosa = Codigo_glosas::select(['codigo_glosas.*', 't.Nombre'])
        ->join('tipos as t', 'codigo_glosas.tipo_id', 't.id ')
        ->where('codigo_glosas.estado_id', 1)
        ->get();

        return response()->json($codigoglosa, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'descripcion'      => 'required',
            'tipo_id'       => 'required',
            'codigo'        => 'unique:codigo_glosas'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $descripcion = strtoupper($request->descripcion);
        $input = $request->all();
        $input['descripcion'] = $descripcion;

        Codigo_glosas::create($input);
        return response()->json([
            'message' => 'Parametrización creada con exito!',
        ], 201);
    }

    public function update(Codigo_glosas $codigoglosa){

        $codigoglosa->update([
            'estado_id' => 2
        ]);
        
        return response()->json([
            'message' => 'Codigo glosa actualizado con exito!',
        ], 200);

    }

    public function tipos(){

        $tipo = Tipo::where('Descripcion', 'Cuentas Medicas')->get();
        return response()->json($tipo, 200);

    }
}
