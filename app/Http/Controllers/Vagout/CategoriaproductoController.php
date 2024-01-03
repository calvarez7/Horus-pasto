<?php

namespace App\Http\Controllers\Vagout;

use Illuminate\Http\Request;
use App\Modelos\Categoriaproducto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoriaproductoController extends Controller
{
    public function allCategory()
    {
        $categoriaproducto = Categoriaproducto::all(['id', 'estado_id', 'nombre', 'descripcion',]);
        return response()->json($categoriaproducto, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nombre'    => 'required|string|unique:categoriaproductos',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input = $request->all();
        $categoriaproducto  = Categoriaproducto::create($input);
        return response()->json([
            'message' => 'Categoriaproducto Creado con Exito!',
        ], 201);
    }

    public function update(Request $request, Categoriaproducto $categoria)
    {
        $categoria->update($request->all());
        return response()->json([
            'message' => 'Categoriaproducto Actualizada con Exito!',
        ], 200);
    }

    public function available(Request $request, Categoriaproducto $categoriaproductos)
    {
        $categoriaproducto->update([
            'Estado_id' => $request->Estado,
        ]);
        return response()->json([
            'message' => 'Estado de la Categoriaproducto Actualizada con Exito!',
        ], 200);
    }
}
