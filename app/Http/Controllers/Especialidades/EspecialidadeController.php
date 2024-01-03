<?php

namespace App\Http\Controllers\Especialidades;

use App\User;
use App\Modelos\Cuporden;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Especialidade;
use App\Modelos\Model_has_role;
use App\Modelos\EspecialidadesCup;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Modelos\CupsordenCitapaciente;
use App\Modelos\Especialidadtipoagenda;
use Illuminate\Support\Facades\Validator;

class EspecialidadeController extends Controller
{
    public function all()
    {
        $especialidad = Especialidade::where('Estado_id', 1)->get();
        return response()->json($especialidad, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'      => 'required|string|unique:Especialidades',
            'Descripcion' => 'string|Max:100',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input        = $request->all();
        $especialidad = Especialidade::create($input);
        return response()->json([
            'message' => 'Especialidad creada con exito!',
        ]);
    }

    public function update(Request $request, Especialidade $especialidade)
    {
        $especialidade->update($request->all());
        return response()->json([
            'message' => 'Especialidad actualizada con exito!',
        ], 200);
    }

    public function available(Request $request, Especialidade $especialidade)
    {
        $especialidade->update([
            'Estado' => $request->Estado,
        ]);
        return response()->json([
            'message' => 'Estado de la Especialidad Actualizada con Exito!',
        ], 200);
    }

    public function especialidadMedico($especialidad)
    {
        $role = Role::where('name', $especialidad)->first();
        $especialidadMedico = Model_has_role::select('u.name', 'u.id', 'u.apellido', 'u.cedula')
        ->join('Users as u', 'Model_has_roles.model_id', 'u.id')
        ->where('Model_has_roles.role_id', $role->id)
        ->get();
        return response()->json($especialidadMedico, 200);
    }

    public function especialidadActividad(Especialidade $especialidade)
    {
        $tipoactividad = Especialidadtipoagenda::where('especialidade_tipoagenda.Especialidad_id', $especialidade->id)
            ->select(['tipo_agendas.id as tipoAgenda','especialidade_tipoagenda.id',
            'Especialidades.Nombre as nombreEspecialidad', 'tipo_agendas.name as nombreActividad','tipo_agendas.modalidad', 'especialidade_tipoagenda.Duracion'])
            ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', 'tipo_agendas.id')
            ->join('Especialidades', 'especialidade_tipoagenda.Especialidad_id', 'Especialidades.id')
            ->where('Especialidades.Estado_id', 1)
            ->where('tipo_agendas.Estado_id', 1)
            ->where('especialidade_tipoagenda.Estado_id', 1)
            ->get();
        return response()->json($tipoactividad, 200);

    }

    public function storespecialidadcups(Request $request){

        foreach($request->cups as $cup){
            EspecialidadesCup::create([
                'especialidad_id' => $request->especialidad,
                'cup_id' => $cup
            ]);
        }

        return response()->json([
            'message' => 'Especialidad cups creada con exito!',
        ], 200);
    }

    public function allespecialidadcups(){

        $especialidadCups = EspecialidadesCup::select(['especialidades.Nombre as especialidad',
        'cups.Codigo', 'cups.Nombre', 'especialidades_cups.id'])
        ->join('especialidades', 'especialidad_id', 'especialidades.id')
        ->join('cups', 'cup_id', 'cups.id')
        ->where('especialidades_cups.estado_id', 1)
        ->get();

        return response()->json($especialidadCups, 200);

    }

    public function updatespecialidadcups(EspecialidadesCup $especialidad){

        $especialidad->update([
            'estado_id' => 2
        ]);

        return response()->json([
            'message' => 'Especialidad cups inhabilitado con exito!',
        ], 200);

    }

    public function especialidadCita($especialidad){

        $existeEspecialidad = EspecialidadesCup::where('especialidad_id', $especialidad)
        ->where('estado_id', 1)
        ->first();

        if($existeEspecialidad){
            return response()->json(true, 200);
        }else{
            return response()->json(false, 200);
        }

    }

    public function especialidadNoProgramada(Request $request){

        $especialidadCups = EspecialidadesCup::select(['cup_id'])
        ->where('especialidad_id', $request->especialidad)
        ->where('estado_id', 1)
        ->get();

        $cups = [];
        foreach ($especialidadCups as $key){
            $cups[] = $key->cup_id;
        }

        $ordens = citapaciente::select(['cups.Nombre', 'cups.id', 'cupordens.Orden_id', 'cupordens.id as cuporden_id',
                'ordens.created_at', 'ordens.Fechaorden',
                DB::RAW("CONCAT(cupordens.Orden_id,'-',cups.Nombre) as NombreMostrar")])
                ->join('ordens', 'ordens.Cita_id', 'cita_paciente.id')
                ->join('cupordens', 'cupordens.Orden_id', 'ordens.id')
                ->join('cups', 'cupordens.Cup_id', 'cups.id')
                ->join('sedeproveedores', 'cupordens.Ubicacion_id', 'sedeproveedores.id')
                ->join('pacientes','pacientes.id','cita_paciente.Paciente_id')
                ->where('pacientes.Num_Doc', $request->cedula_paciente)
                ->whereIn('cupordens.Estado_id', [1,7])
                ->whereIn('cupordens.Cup_id', $cups)
                ->where('cupordens.atendida', null)
                ->get();
            if($ordens){
                return response()->json($ordens, 200);
            }else{
                return response()->json(false, 200);
            }
    }

    public function guardarOrden(Request $request)
    {
        $existeCupOrden = CupsordenCitapaciente::where('cupordens_id', $request->cuporden_id)->first();
        $marcarCups = Cuporden::where('id',$request->cuporden_id)->first();
        if($existeCupOrden){
            $marcarCups->update([
                'atendida' => 1,
            ]);
            $existeCupOrden->update([
                'citapaciente_id' => $request->citaPaciente
            ]);
            }else{
                CupsordenCitapaciente::create([
                    'cupordens_id' => $request->cuporden_id,
                    'citapaciente_id' => $request->citaPaciente
                    ]);
                }
                $marcarCups->update([
                    'atendida' => 1,
                ]);
        return response()->json([
            'message' => 'Se Guardo con Exito!'], 200);
    }

}
