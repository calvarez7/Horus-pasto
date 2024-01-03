<?php

namespace App\Http\Controllers\Especialidadtipoagendas;

use App\Http\Controllers\Controller;
use App\Modelos\Especialidade;
use App\Modelos\Especialidadtipoagenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspecialidadtipoagendaController extends Controller
{

    public function all()
    {
        $especialidadtipoagenda = Especialidadtipoagenda::select([
                'especialidade_tipoagenda.*', 'Especialidades.Nombre as nombreEspecilidad', 'tipo_agendas.name as nombreActividad'
            ])
            ->join('Especialidades', 'especialidade_tipoagenda.Especialidad_id', 'Especialidades.id')
            ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', 'tipo_agendas.id')
            ->where('Especialidades.Estado_id', 1)
            ->where('tipo_agendas.Estado_id', 1)
            ->where('especialidade_tipoagenda.estado_id', 1)
            ->get();
        return response()->json($especialidadtipoagenda, 200);
    }

    public function allEspecialidades()
    {
        $especialidadtipoagenda = Especialidadtipoagenda::select([
                'especialidade_tipoagenda.*', 'Especialidades.Nombre as nombreEspecilidad', 'tipo_agendas.name as nombreActividad'
            ])
            ->join('Especialidades', 'especialidade_tipoagenda.Especialidad_id', 'Especialidades.id')
            ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', 'tipo_agendas.id')
            ->get();
        return response()->json($especialidadtipoagenda, 200);
    }

    public function store(Request $request, Especialidade $especialidade)
    {

        $validate = Validator::make($request->all(), [
            'Duracion' => 'required',
        ]);

        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $especialidadtipoagenda                  = new Especialidadtipoagenda();
        $especialidadtipoagenda->Tipoagenda_id   = $request->Tipoagenda_id;
        $especialidadtipoagenda->Especialidad_id = $especialidade->id;
        $especialidadtipoagenda->Primeravez_id   = $request->Primeravez_id;
        $especialidadtipoagenda->Control_id      = $request->Control_id;
        $especialidadtipoagenda->Duracion        = $request->Duracion;
        $especialidadtipoagenda->save();
        return response()->json([
            'message' => 'Se guardo con Exito!',
        ], 200);
    }

    public function getActivityBySpecialty(Especialidade $especialidade)
    {

        $especialidadtipoagenda = Especialidadtipoagenda::select(['especialidade_tipoagenda.id', 'e.Nombre as nombreEspecilidad', 'ta.name as nombreActividad'])
            ->join('Especialidades as e', 'especialidade_tipoagenda.Especialidad_id', 'e.id')
            ->join('tipo_agendas as ta', 'especialidade_tipoagenda.Tipoagenda_id', 'ta.id')
            ->where('e.id', $especialidade->id)
            ->where('e.Estado_id', 1)
            ->where('ta.Estado_id', 1)
            ->where('especialidade_tipoagenda.estado_id', 1)
            ->get();

        return response()->json($especialidadtipoagenda, 200);
    }

    public function cambioEstado(int $id) {
        $estado = Especialidadtipoagenda::where('id',$id)->first();

        if($estado->estado_id == 1){
            $estado->update([
                'estado_id' => 2
            ]);
        }else{
            $estado->update([
                'estado_id' => 1
            ]);
        }

        return response()->json([
            'message' => 'Se Actualiza el estado con Exito!'], 200);
    }

    public function update(Request $request, Especialidadtipoagenda $especialidadtipoagenda)
    {
        //
    }

    public function destroy(Especialidadtipoagenda $especialidadtipoagenda)
    {
        //
    }
}
