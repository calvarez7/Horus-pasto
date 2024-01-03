<?php

namespace App\Http\Controllers\Tiporequerimientos;

use App\Http\Controllers\Controller;
use App\Modelos\Tiporequerimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TiporequerimientoController extends Controller
{
    public function all()
    {
        $tiporequerimientos = Tiporequerimiento::all(['id', 'Nombre', 'Dias']);
        return response()->json($tiporequerimientos, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string|unique:tiporequerimientos',
            'Dias'   => 'required|integer',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $tiporequerimientos = Tiporequerimiento::create([
            'Nombre'  => $request->Nombre,
            'Dias'    => $request->Dias,
            'User_id' => auth()->user()->id,

        ]);
        return response()->json([
            'message' => 'Tipo de Requerimiento creado con !Exito',
        ], 201);
    }
}
