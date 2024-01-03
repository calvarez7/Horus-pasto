<?php

namespace App\Http\Controllers\Configuraciones;

use App\Http\Controllers\Controller;
use App\Modelos\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{

    public function getConfiguraciones(){
        return response()->json(
            Configuracion::find(1)
        );
    }

    public function guardarConfiguraciones(Request $request){
        Configuracion::where('id', 1)->update($request->except(['id', 'created_at', 'updated_at']));
        return response()->json("Actualizacion Exitosa!");
    }
}
