<?php

namespace App\Http\Controllers\Juzgados;

use App\Http\Controllers\Controller;
use App\Modelos\Jusgado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JuzgadoController extends Controller
{
    public function all()
    {
        // $juzgados = Jusgado::all();
        $juzgados = Jusgado::all(['id', 'Nombre']);
        return response()->json($juzgados, 200);
    }

    public function store(Request $request)
    {
        // return 'estamos aca';
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string|unique:jusgados',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input    = $request->all();
        $juzgados = Jusgado::create($input);

        return response()->json([
            'message' => 'Juzgado creado con exito!',
        ], 201);
    }
}
