<?php

namespace App\Http\Controllers\Desarrollo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\AsignacionActividadMail;
use App\Modelos\ActividadDesarrollo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ActividadDesarrolloController extends Controller
{

    protected $errors;

    /**
     *lista activiades
     *@return json $actividades->id
     *@author
     */
    public static function listar()
    {
        $actividades = ActividadDesarrollo::whereHas('responsables', function(Builder $query) {
            $query->where('user_id',auth()->id());
        })->get();

        return response()->json($actividades, 200);
    }

    /**
     *almacena una actividad
     *@param Request $request
     *@return json
     *@author
     */
    public function guardar(Request $request)
    {

        if(!$this->validar($request)){
            return response()->json($this->errors, 422);
        };

        $input = $request->except(['reponsables']);
        $input['created_by'] = 1;
        $input['estado_id'] = 5;

        try {
            DB::beginTransaction();

            $actividad = ActividadDesarrollo::create($input);
            $actividad->responsables()->sync($request->responsables);

            Mail::to($actividad->responsables)
                ->send(new AsignacionActividadMail($actividad));

            DB::commit();
            return response()->json(['message' => 'Los datos se crearon con exito!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    /**
     *Actualiza una actividad
     *@param Request $request
     *@param int $id
     *@return json
     *@author
     */
    public function actualizar(Request $request,  $id)
    {

        if(!$this->validar($request)){
            return response()->json($this->errors, 422);
        };

        $input = $request->except(['reponsables']);
        $actividad = ActividadDesarrollo::where('id',$id)->firstOrFail();

        try {
            DB::beginTransaction();
            $actividad->update($input);
            $actividad->responsables()->sync($request->responsables);
            DB::commit();
            return response()->json(['message' => 'Actividad de desarrollo actualizada con exito!'],200);
        } catch (\Throwable $th) {
             DB::rollBack();
             return response()->json(['message' => 'Ha ocurrido un error!'],400);

        }

    }

     /**
     *Actualiza el estado de una actividad
     *@param Request $request
     *@param int $id
     *@return json
     *@author
     */
    public function cambiarEstado(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'estado_id' => 'required|exists:estados,id'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(), 422);
        }
        $actividad = ActividadDesarrollo::where('id',$id)->first();
        if (!$actividad) {
            return response()->json(['message' => 'La actividad no existe'], 404);
        }

        $actividad->update($request->all());
        return response()->json(['message' => 'Estado de la actividad actualizado con Exito!'], 200);
    }

     /**
     *Consulta una actividad
     *@param int $id
     *@return json
     *@author
     */
    public function consultar($id)
    {
       $actividad = ActividadDesarrollo::where('id',$id)->with('responsables', 'archivos', 'creador')->first();
            return response()->json($actividad, 200);
    }

     /**
     *Actualizar una actividad
     *@param Request $request
     *@param int $id
     *@return json
     *@author
     */
    public function actualizarFecha(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'tiempo_fin' => 'required|date'
        ]);

        if ($validate->fails()) {
                return response()->json($validate->errors(), 422);
        }

        $actividad = ActividadDesarrollo::where('id',$id)->first();
            if ($request->tiempo_fin < $actividad->tiempo_inicio) {
                    return response()->json(['message' => 'La fecha final debe ser mayor a la fecha de inicio.'], 422);
        }
        $actividad->update($request->all());
            return response()->json(['message' => 'Fecha final actualizada con exito!'], 200);
    }

    protected function validar(Request $request)
    {
       $validate= Validator::make($request->all(),[
            'titulo' => 'required',
            'detalle' => 'required',
            'responsables' => 'required|array',
            'tiempo_inicio' => 'required',
            'tiempo_fin' => 'required '
       ]);

       if ($validate->fails()) {
                $this->errors=$validate->Errors();
                return false;
       }
       if ($request->tiempo_inicio > $request->tiempo_fin) {
            $this->errors=['message' => 'El timepo de inicio debe ser menor al tiempo final'];
            return false;
       }

      return true;
    }

}
