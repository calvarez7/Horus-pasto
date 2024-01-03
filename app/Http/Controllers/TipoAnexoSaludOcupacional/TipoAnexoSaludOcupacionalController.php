<?php

namespace App\Http\Controllers\TipoAnexoSaludOcupacional;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarTipoAnexoSaludOcupacionalRequest;
use App\Modelos\TipoAnexoSaludOcupacional;

class TipoAnexoSaludOcupacionalController extends Controller
{
    public function guardar(GuardarTipoAnexoSaludOcupacionalRequest $request){
        try {
            $guardar = TipoAnexoSaludOcupacional::create($request->all());
        return response()->json(['data'=>$guardar,
        'mensaje' => 'El tipo de anexo se creo de manera exitosa'
        ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['error' =>$th->getMessage(),
        'mensaje' => 'Error al crear el tipo anexo'], Response::HTTP_BAD_REQUEST);
        }

    }

    public function listar(){
        try {
            $listar = TipoAnexoSaludOcupacional::select('nombre','id')->orderBy('created_at','desc')->get();
            return response()->json([
               'data' => $listar
            ],Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['error'=> $th->getMessage(),'mensaje' => 'No se pudo listar los tipos de anexo'
        ], Response::HTTP_BAD_REQUEST);
        }
    }
}
