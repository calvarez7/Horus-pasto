<?php

namespace App\Http\Controllers\Clasificacion;

use App\Http\Controllers\Controller;
use App\Modelos\clasificacion;
use App\Modelos\clasificacionPaciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClasificacionController extends Controller
{

    public function getClasificacion(){
        $clasificacion = clasificacion::select(
            DB::raw("CONCAT(users.name, ' ', users.apellido) as creado_por"),
            'clasificacions.id as clasificacion_id',
            'clasificacions.nombre as clasificacion_nombre',
            'clasificacions.descripcion',
            'clasificacions.estado')
            ->join('users','users.id','clasificacions.user_id')
            ->get();
        return response()->json($clasificacion, 200);
    }

    public function guardarClasificacion(Request $request){
        $clasificacion = clasificacion::create([
            'nombre' => $request->clasificacion_nombre,
            'descripcion' => $request->descripcion,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'La clasificación se guardo con exito!'
        ], 201);
    }

    public function actualizacionClasificacion($id,Request $request){

        $clasificacion = clasificacion::where('id',$id);
        
        $clasificacion->update([
            'nombre' => $request->clasificacion_nombre,
            'descripcion' => $request->descripcion,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'La clasificación se actualizo con exito!'], 200);
    }

    public function actualizacionClasificacionEstado($id){

        $clasificacion = clasificacion::where('id',$id)->first();

        if($clasificacion->estado == true){
            $clasificacion->update([
                'estado' => false,
                'user_id' => auth()->user()->id,
            ]);
        }else{
            $clasificacion->update([
                'estado' => true,
                'user_id' => auth()->user()->id,
            ]);
        }

        return response()->json([
            'message' => 'La clasificación se actualizo con exito!'], 200);
    }

    public function getClasificacionActivos(){
        $clasificacion = clasificacion::select('id',
            DB::raw("CONCAT(clasificacions.nombre,' - ',clasificacions.descripcion) as clasificacion_nombre"))
            ->where('estado',1)
            ->get();
        return response()->json($clasificacion, 200);
    }

    public function guardarClasificacionPaciente(Request $request){
        $verificacion = clasificacionPaciente::where('clasificacion_id',$request->clasificacion_id)->first();
            if(!$verificacion){
                $clasifiacionPaciente = clasificacionPaciente::create([
                    'clasificacion_id' => $request->clasificacion_id,
                    'paciente_id' => $request->paciente_id,
                    'user_id' => auth()->user()->id,
                ]);
                return response()->json($clasifiacionPaciente, 201);
            }else{
                return response()->json(['mensaje'=> 'El paciente ya tiene esta clasifiación'], 400);
            }

       
    }

    public function getClasificacionPacienteActivos($id){
        
        $clasifiacionPaciente = clasificacionPaciente::select(
            'clasificacion_pacientes.id as clasificacion_paciente_id',
            DB::raw("CONCAT(users.name, ' ', users.apellido) as creado_por"),
            DB::raw("CONCAT(clasificacions.nombre,' - ',clasificacions.descripcion) as clasificacion_nombre"))
        ->join('pacientes','pacientes.id','clasificacion_pacientes.paciente_id')
        ->join('clasificacions','clasificacions.id','clasificacion_pacientes.clasificacion_id')
        ->join('users','users.id','clasificacion_pacientes.user_id')
        ->where('clasificacion_pacientes.paciente_id',$id)
        ->get();

        return response()->json($clasifiacionPaciente, 200);
    }

    public function eliminarClasificacionPaciente(clasificacionPaciente $id){
        
        $id->delete();
        return response()->json([ 'message' => 'Se a Desclasificado correctamente!'],200);
    }
}
