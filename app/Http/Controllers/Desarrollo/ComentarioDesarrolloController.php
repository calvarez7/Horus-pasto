<?php

namespace App\Http\Controllers\Desarrollo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modelos\ActividadDesarrollo;
use Illuminate\Support\Facades\Auth;
use App\Modelos\ComentarioDesarrollo;
use Illuminate\Support\Facades\Validator;

class ComentarioDesarrolloController extends Controller
{
   /**
     *listar comentarios
     *@param int $id
     *@return json
     *@author
     */
    public function consultar($id)
    {
        $comentario = ComentarioDesarrollo::where('actividad_desarrollo_id', $id)->get();
        return response()->json($comentario, 200);
    }
    /**
     *almacenar un comentarios
     *@param Request $request
     *@param int $id
     *@return json
     *@author
     */
    public function guardar(Request $request, $id)
    {

        if (!$this->validar($request,$id)) {
            return response()->json($this->errors, 422);
        }

        if(!ActividadDesarrollo::where('id',$id)->exists()){
            return response()->json(['message' => 'Actividad no existe'], 404);
        }

        ComentarioDesarrollo::create([
            'comentario' => $request->comentario,
            'actividad_desarrollo_id' => $id,
            'user_id'=> auth()->id()
        ]);

        return response()->json(['message' => 'Comentario creado con exito!'], 201);
    }

    /**
     *actualizar un comentarios
     *@param Request $request
     *@param int $id
     *@return json
     *@author
     */
    public function actualizar(Request $request, $id)
    {
        if (!$this->validar($request,$id)) {
            return response()->json($this->errors, 422);
        }

        $comentario = ComentarioDesarrollo::where('id',$id)->first();
        if(!$comentario){
            return response()->json(['message' => 'El comentario no existe'], 404);
        }

        $comentario->update([
            'comentario' => $request->comentario,
        ]);

        return response()->json(['message' => 'Comentario actualizado correctamente.'], 200);
    }

    /**
     * Valida el request
     * @param Request $request
     * @param int $id
     * @return boolean true|false
     */
    protected function validar($request, $id)
    {
        $validate = Validator::make($request->all(),[
            'comentario' => 'required|string'
        ]);

        if($validate->fails()){
            $this->errors = $validate->errors();
            return false;
        }

        return true;
    }

    /**
     * Eliminar un comentario
     * @param int $id
     * @return json
     */
     public function eliminar($id)
     {
      $comentario = ComentarioDesarrollo::where('id', $id)->firstOrFail();
      $comentario->delete();
      return response()->json(['message' => 'Comentario eliminado correctamente.'], 200);
     }


}

