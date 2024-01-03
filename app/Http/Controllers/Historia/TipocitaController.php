<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\Tipocita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipocitaController extends Controller
{
    public function all()
    {
        $tipocita = Tipocita::select('Tipocitas.*', 'e.Nombre as NomEstado')
            ->join('Estados as e', 'Tipocitas.Estado_id', 'e.id')
            ->where('Tipocitas.Estado_id', 1)
            ->where('Tipocitas.id', '<>', 2)
            ->get();
        return response()->json($tipocita, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'      => 'required|string|unique:Tipocitas',
            'Descripcion' => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input = $request->all();
        Tipocita::create($input);

        return response()->json([
            'message' => 'Tipoactencion creado con exito!',
        ], 201);
    }

    public function update(Request $request, Tipocita $tipocita)
    {
        $tipocita->update($request->all());
        return response()->json([
            'message' => 'Tipocita actualizado con exito!',
        ], 200);
    }

    public function available(Request $request, Tipocita $tipocita)
    {
        $tipocita->update([
            'Estado_id' => $request->Estado_id,
        ]);
        return response()->json([
            'message' => 'Tipocita inhabilitado con exito!',
        ], 200);
    }
}
