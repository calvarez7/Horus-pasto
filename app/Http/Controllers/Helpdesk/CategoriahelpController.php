<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use App\Modelos\Categoriahelp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriahelpController extends Controller
{
    public function getcategoria($area)
    {
        $categoriahelp = Categoriahelp::where('Area_id', $area)->get();
        return response()->json($categoriahelp, 201);
    }

    public function allcategoria()
    {
        $allcategoriahelp = Categoriahelp::select('categorias_help.id as id', 'categorias_help.Nombre as Nombre',
            'categorias_help.Descripcion as Descripcion', 'categorias_help.alerta','areas_help.Nombre as Area')
            ->join('areas_help', 'categorias_help.Area_id', 'areas_help.id')
            ->get();

        return response()->json($allcategoriahelp, 201);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'      => 'required|string|unique:categorias_help',
            'Descripcion' => 'required|string',
            'Area_id'     => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $input     = $request->all();
        $categoria = Categoriahelp::create($input);

        return response()->json([
            'message' => 'categoria creada con exito!',
        ], 201);
    }

    public function crearAlerta($id, Request $request){

        $validate = Validator::make($request->all(), [
            'alerta'     => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $categoria = Categoriahelp::where('id',$id)->first();
        $categoria->update($request->except(['id']));
        return response()->json([
            'message' => 'alerta creada con exito!',
        ], 200);
    }
}
