<?php

namespace App\Http\Controllers\Teleconcepto;

use App\Http\Controllers\Controller;
use App\Modelos\AdjuntoTeleconcepto;
use App\Modelos\Cie10;
use App\Modelos\Cie10paciente;
use App\Modelos\citapaciente;
use App\Modelos\Cup;
use App\Modelos\IntegrantesJuntaGirs;
use App\Modelos\Paciente;
use App\Modelos\Teleconcepto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class TeleconceptoController extends Controller
{
    public function store(Request $request)
    {
        //return response()->json(['message' => $request->all()], 200);
//        dd($request->all());
        $citapaciente = citapaciente::create([
            'Paciente_id' => $request->id_paci,
            'Usuario_id' => auth()->user()->id,
            'Tipocita_id' => 8,
//            'Cup_id' => $request->cup_id,
            'Motivoconsulta' => $request->motivo,
        ]);

        $tele = Teleconcepto::create([
            'Paciente_id'    => $request->id_paci,
            'UserCrea_id'    => auth()->user()->id,
            'Tipo_Solicitud' => $request->tipoSolicitud,
            'descripcion'    => $request->motivo,
            'ResumenHc'      => $request->resumen,
            'especialidad'      => $request->especialidad,
            'Estado_id'      => 18,
            'girs'           => $request->girs === "true"?1:false,
            'cita_paciente_id' => $citapaciente->id
        ]);


        $paciente = Paciente::select('Pacientes.*', 'sedeproveedores.Nombre as NombreIPS', 'e.nombre as entidad')
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->where('Pacientes.id', $request->id_paci)
            ->where('Estado_Afiliado', 1)
            ->whereIn('pacientes.entidad_id', [1,2,3])
            ->first();

        $cie10s = Cie10::find($request->cie10s);
        $tele->cie10s()->attach($cie10s);
        foreach ($request->cie10s as $key => $cie10) {
            Cie10paciente::create([
                "Cie10_id" => $cie10,
                "Citapaciente_id" => $citapaciente->id,
                "Esprimario" => ($key === 0?1:0),
                'Tipodiagnostico' => null
            ]);
        }

        $files = $request->adjuntos;

        if (isset($files)) {
            foreach ($files as $file) {
                $store = $file->store('/public/upload_teleconceptos');
                $path  = '/storage/upload_teleconceptos/' . explode('/', $store)[2];
                $tele->adjuntos()->save(new AdjuntoTeleconcepto(['Ruta' => $path]));
            }
        }

        return response()->json([
            'citaPaciente' => $citapaciente->id,
            'paciente' => $paciente,
            'teleconcepto' => $tele->id,
            'message' => 'Teleconcepto creado',
        ], 200);
    }

    public function reply(Teleconcepto $tele, Request $request)
    {
        $tele->update([
            'UserResponde_id' => auth()->user()->id,
            'Respuesta'       => $request->Respuesta,
            'Estado_id'       => $tele->girs_auditor?18:9, //atendida
            'pertinencia_prioridad' => $request->pertinencia_prioridad,
            'pertinencia_evaluacion' => $request->pertinencia_evaluacion,
        ]);

        return response()->json([
            'message' => 'Teleconcepto respondido con exito!',
            "tele"    => $tele,
        ], 200);
    }

    public function pendingTeleconcepto(Request $request)
    {
        if (auth()->user()->can('teleconcepto.pendientes.ver') || auth()->user()->can('dev')) {
            $teleconceptos = $this->allPendingTeleconcepto($request->all());
        } else {
            $teleconceptos = $this->myPendingTeleconcepto($request->all());
        }

        return response()->json($teleconceptos, 200);
    }

    private function myPendingTeleconcepto($filtros = null)
    {
        $query = Teleconcepto::select(['teleconceptos.*',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'us.Firma',
            'us.Registromedico',
            'us.especialidad_medico',
            'p.Ut',
            'u.name',
            'u.apellido',
            's.Nombre as Sede',
            'cp.Cup_id'
        ])
            ->join('pacientes as p', 'teleconceptos.Paciente_id', 'p.id')
            ->join('users as u', 'teleconceptos.UserCrea_id', 'u.id')
            ->join('users as us', 'teleconceptos.UserCrea_id', 'us.id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->leftjoin('cita_paciente as cp','cp.id','teleconceptos.cita_paciente_id')
            ->with(['cie10s', 'adjuntos','integrantesGirs'])
            ->where('teleconceptos.Estado_id', 18)
            ->where('teleconceptos.UserCrea_id', auth()->user()->id)
            ->whereNull('girs_auditor');
        if($filtros){
            if($filtros["girs"] === true){
                $query->where('teleconceptos.girs',true);
            }
            if($filtros["prioridad"] != 'Todas'){
                $query->where('teleconceptos.Tipo_Solicitud',$filtros["prioridad"]);
            }
            if($filtros["fecha_inicio"] != null){
                $query->whereBetween('teleconceptos.created_at', [$filtros["fecha_inicio"], $filtros["fecha_final"]]);
            }
        }


        $teleconceptos = $query->orderBy('teleconceptos.created_at','DESC')->get();
        return $teleconceptos;
    }

    private function allPendingTeleconcepto($filtros = null)
    {
        $permisos = auth()->user()->getAllPermissions();

        $query = Teleconcepto::select(['teleconceptos.*',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'us.Firma',
            'us.Registromedico',
            'us.especialidad_medico',
            'p.Ut',
            'u.name',
            'u.apellido',
            's.Nombre as Sede',
            'cp.Cup_id'
        ])
            ->join('pacientes as p', 'teleconceptos.Paciente_id', 'p.id')
            ->join('users as u', 'teleconceptos.UserCrea_id', 'u.id')
            ->join('users as us', 'teleconceptos.UserCrea_id', 'us.id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->leftjoin('cita_paciente as cp','cp.id','teleconceptos.cita_paciente_id')
            ->with(['cie10s', 'adjuntos','integrantesGirs'])
            ->where('teleconceptos.Estado_id', 18)
            ->whereNull('teleconceptos.girs_auditor');

        if($filtros){
            if($filtros["girs"] === true){
                $query->where('teleconceptos.girs',true);
            }
            if($filtros["prioridad"] != 'Todas'){
                $query->where('teleconceptos.Tipo_Solicitud',$filtros["prioridad"]);
            }
            if($filtros["fecha_inicio"] != null){
                $query->whereBetween('teleconceptos.created_at', [$filtros["fecha_inicio"], $filtros["fecha_final"]]);
            }
        }
        if (!auth()->user()->can('teleconcepto.pendientes.todos')) {
            $query->where(function($query) use ($permisos) {
                foreach ($permisos as $permiso) {
                    $query->orWhere('teleconceptos.especialidad',$permiso->name);
                }
            });
        }
            $query->orWhere(function($query) use ($filtros){
                $query->where('teleconceptos.Estado_id', 18)
                    ->whereNull('teleconceptos.girs_auditor')
                    ->where('teleconceptos.UserCrea_id', auth()->user()->id);
                if($filtros){
                    if($filtros["girs"] === true){
                        $query->where('teleconceptos.girs',true);
                    }
                    if($filtros["prioridad"] != 'Todas'){
                        $query->where('teleconceptos.Tipo_Solicitud',$filtros["prioridad"]);
                    }
                }
            });


        $teleconceptos = $query->orderBy('teleconceptos.created_at','DESC')->get();
        return $teleconceptos;
    }

    public function solvedTeleconcepto($cedula_paciente)
    {
//        if (auth()->user()->can('teleconcepto.reply') || auth()->user()->can('dev')) {
            $teleconceptos = $this->allSolvedTeleconcepto($cedula_paciente);
//        } else {
//            $teleconceptos = $this->mySolvedTeleconcepto();
//        }

        return response()->json($teleconceptos, 200);

    }

    private function allSolvedTeleconcepto($cedula_paciente)
    {

        $teleconceptos = Teleconcepto::select(['teleconceptos.*',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'u.name',
            'u.apellido',
            'us.Firma',
            'us.Registromedico',
            'us.especialidad_medico',
            'p.Ut',
            's.Nombre as Sede'])
            ->join('pacientes as p', 'teleconceptos.Paciente_id', 'p.id')
            ->join('users as u', 'teleconceptos.UserCrea_id', 'u.id')
            ->join('users as us', 'teleconceptos.UserResponde_id', 'us.id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->with(['adjuntos','integrantesGirs'])
            ->where('teleconceptos.Estado_id', 9)
            ->where('p.Num_Doc',$cedula_paciente)
//            ->where(function($query) use ($permisos) {
//                foreach ($permisos as $permiso) {
//                    $query->orWhere('teleconceptos.especialidad',$permiso->name);
//                }
//            })
//            ->orWhere(function($query){
//                $query->where('teleconceptos.Estado_id', 9)
//                ->where('teleconceptos.UserCrea_id', auth()->user()->id);
//            })
            ->orderBy('teleconceptos.updated_at', 'DESC')
            ->get();

        return $teleconceptos;
    }

    private function mySolvedTeleconcepto()
    {
        $teleconceptos = Teleconcepto::select(['teleconceptos.*',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'p.Ut',
            'u.name',
            'u.apellido',
            'us.Firma',
            'us.Registromedico',
            'us.especialidad_medico',
            's.Nombre as Sede'])
            ->join('pacientes as p', 'teleconceptos.Paciente_id', 'p.id')
            ->join('users as u', 'teleconceptos.UserCrea_id', 'u.id')
            ->join('users as us', 'teleconceptos.UserResponde_id', 'us.id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->with(['cie10s', 'adjuntos','integrantesGirs'])
            ->where('teleconceptos.Estado_id', 9)
            ->orderBy('teleconceptos.updated_at', 'DESC')
            ->where('teleconceptos.UserCrea_id', auth()->user()->id)
            ->get();

        return $teleconceptos;
    }

    public function counter()
    {
        $pendientes   = Teleconcepto::where('Estado_id', 18)->count();
        $solucionadas = Teleconcepto::where('Estado_id', 9)->count();

        $total = $pendientes + $solucionadas;

        return response()->json(
            [
                'pendientes'   => $pendientes,
                'solucionadas' => $solucionadas,
                'total'        => $total,
            ], 200);
    }

    public function informeTeleconcepto(Request $request)
    {
//        $appointments = Collect(DB::select("exec dbo.ExportTeleconcepto ?,?", [$request->startDate,$request->finishDate]));
        $info =['id' => null, 'cantidad' => 0];
        $appointments = json_decode(json_encode(DB::select("exec dbo.ExportTeleconcepto ?,?", [$request->startDate,$request->finishDate])),true);
        foreach ($appointments as $key => $appointment){
            $servicios = Cup::select(['cups.Nombre as servicio solicitado','cups.Codigo as cups del servicio solicitado','e.Nombre as estado','c.valor_total as costo'])
                ->join('cupordens as c','cups.id','c.Cup_id')
                ->join('ordens as o', 'c.Orden_id' , 'o.id')
                ->join('cita_paciente as cp','o.Cita_id','cp.id')
                ->join('teleconceptos as t','cp.id','t.cita_paciente_id')
                ->join('estados as e' ,'c.Estado_id', 'e.id')
                ->where('t.id',$appointments[0]['#Solicitud'])->get()->toArray();
            if(count($servicios) > $info['cantidad']){
                $info['id'] = $key;
                $info['cantidad'] = count($servicios);
            }
            $flag = 1;
            foreach ($servicios as $servicio){
                foreach ($servicio as $key2 => $value){
                    $appointments[$key][$key2.$flag] = $value;
                }
                $flag++;
            }
        }
        if($info['cantidad'] > 0){
            $principio = $appointments[$info['id']];
            unset($appointments[$info['id']]);
            $final = array_merge([$principio],$appointments);
        }else{
            $final = $appointments;
        }

        return (new FastExcel(collect($final)))->download('file.xls');
    }

    public function reasignarGIRS(Request $request){
        Teleconcepto::where('id',$request->id)
            ->update(['observacion_reasignacion_girs' => $request->observacion,'girs_auditor'=> 1]);
        return response()->json('Reasignado con exito!');
    }

    public function actualizarPertinencia(Request $request){
        Teleconcepto::where('id',$request->id)
            ->update($request->except(['id']));
        return response()->json('Pertinencia actualizada con exito!');
    }

    public function guardar(Request $request){
        Teleconcepto::where('id',$request->id)
            ->update($request->except(['id']));
        return response()->json('Registro Exitoso!');
    }
    public function guardarIntegrantes(Request $request,$teleconcepto){
        foreach ($request->all() as $user){
            IntegrantesJuntaGirs::create(['teleconcepto_id'=> $teleconcepto,'usuario_id'=>$user]);
        }
        return response()->json('Registro Exitoso!');
    }

    public function girsAuditados(){
        $teleconceptos = Teleconcepto::select(['teleconceptos.*',
            'p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Edad_Cumplida',
            'p.Telefono',
            'p.Celular1',
            'p.Celular2',
            'p.Correo1',
            'p.Correo2',
            'us.Firma',
            'us.Registromedico',
            'us.especialidad_medico',
            'p.Ut',
            'u.name',
            'u.apellido',
            's.Nombre as Sede',
            'cp.Cup_id'
        ])
            ->join('pacientes as p', 'teleconceptos.Paciente_id', 'p.id')
            ->join('users as u', 'teleconceptos.UserCrea_id', 'u.id')
            ->join('users as us', 'teleconceptos.UserCrea_id', 'us.id')
            ->join('sedeproveedores as s', 'p.IPS', 's.id')
            ->leftjoin('cita_paciente as cp','cp.id','teleconceptos.cita_paciente_id')
            ->with(['cie10s', 'adjuntos','integrantesGirs'])
            ->where('teleconceptos.Estado_id', 18)
            ->whereNotNull('teleconceptos.girs_auditor')->get();

        return response()->json($teleconceptos);
    }
}
