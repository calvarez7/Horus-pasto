<?php

namespace App\Http\Controllers\Desarrollo;

use App\Modelos\ArchivoDesarrollo;
use App\Http\Controllers\Controller;
use App\Modelos\ActividadDesarrollo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArchivoDesarrolloController extends Controller
{
     /**
     *lista de archivos
     *@param int $id
     *@return json $archivos
     *@author
     */
    public function consultar($id)
    {
        $archivos = ArchivoDesarrollo::where('actividad_desarrollo_id', $id)->get();
        return response()->json($archivos, 200);
    }

     /**
     *guardar de archivos
     *@param  Request $request
     *@return json
     *@author
     */
    public function  guardar(Request $request, $id)
    {

        $validate = Validator::make($request->all(),[
            'archivo' => 'required|file'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        if(!ActividadDesarrollo::where('id',$id)->exists()){
            return response()->json(['message' => 'actividad no existe'], 404);
        }

        $nombre = $request->file('archivo')->getClientOriginalName();

        try {
            $url = Storage::putFile('desarrollo/archivos', $request->file('archivo'));
            ArchivoDesarrollo::create([
                'archivo' =>$nombre,
                'url' => $url,
                'user_id' =>3078,
                'actividad_desarrollo_id' => $id
            ]);
        } catch (\Throwable $th) {
            Storage::delete($url);
            return response()->json(['message' => 'Ha ocurrido un error'],401);
        }

        return response()->json(['message' => 'Se ha almacenado el archivo con exito!'], 201);

    }
    /**
     *eliminar archivos
     *@param int $id
     *@return json
     *@author
     */
    
    public function eliminar($id)
    {
        $archivo = ArchivoDesarrollo::where('id',$id)->firstOrFail()->firstOrFail();
        $archivo->delete();
        return response()->json(['message' => 'El archivo ha sido eliminado con exito!'], 200);

    }


}
