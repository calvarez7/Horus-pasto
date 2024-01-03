<?php

namespace App\Http\Controllers\Vagout;

use App\Modelos\Tipofactura;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipofacturaController extends Controller
{
    public function all()
    {
        $tipofactura = Tipofactura::all(['id', 'estado_id', 'nombre', 'descripcion',]);
        return response()->json($tipofactura, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nombre'    => 'required|string|unique:Tipofacturas',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input = $request->all();
        $Tipofactura  = Tipofactura::create($input);
        return response()->json([
            'message' => 'Tipofactura Creado con Exito!',
        ], 201);
    }

    public function update(Request $request, Tipofactura $Tipofactura)
    {
        $Tipofactura->update($request->all());
        return response()->json([
            'message' => 'Tipofactura Actualizada con Exito!',
        ], 200);
    }

    public function available(Request $request, Tipofactura $Tipofactura)
    {
        $Tipofactura->update([
            'Estado_id' => $request->Estado,
        ]);
        return response()->json([
            'message' => 'Estado del Tipofactura Actualizado con Exito!',
        ], 200);
    }
}
