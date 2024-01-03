<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use App\Modelos\Actividadhelp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActividadhelpController extends Controller
{
    public function getactividad($categoria)
    {
        $actividadhelp = Actividadhelp::where('Categoria_id', $categoria)->get();
        return response()->json($actividadhelp, 201);
    }

    public function allactividad()
    {
        $allactividad = Actividadhelp::select('actividades_help.id as id', 'actividades_help.Nombre as Nombre',
            'actividades_help.Descripcion as Descripcion', 'categorias_help.Nombre as Categoria')
            ->join('categorias_help', 'actividades_help.Categoria_id', 'categorias_help.id')
            ->get();

        return response()->json($allactividad, 201);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'       => 'required|string|unique:actividades_help',
            'Descripcion'  => 'required|string',
            'Categoria_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $input = $request->all();
        $area  = Actividadhelp::create($input);

        return response()->json([
            'message' => 'Actividad creada con exito!',
        ], 201);
    }
}
