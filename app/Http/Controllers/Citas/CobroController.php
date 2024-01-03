<?php
namespace App\Http\Controllers\Citas;
use App\Formats\Medicamento;
use App\Formats\ReciboPago;
use App\Formats\Servicio;
use App\Formats\ServicioMedimas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Entidades\EntidadController;
use App\Modelos\Cie10;
use App\Modelos\citapaciente;
use App\Modelos\Cobro;
use App\Modelos\CumEntidade;
use App\Modelos\Cuporden;
use App\Modelos\Detaarticulorden;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Rap2hpoutre\FastExcel\FastExcel;

class CobroController extends Controller
{
    public function getCobrosByPaciente($identificacion){
        $this->recalcularCobros($identificacion);
        $cobros = Cobro::select([
            'cobros.*',
            'o.id as idOrden',
            'cp.Finalidad',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'to.nombre',
            'to.id as idTipo',
            'cp.id as cita',
            DB::raw("CONCAT(u.name,' ',u.apellido) as usuario"),
            'cp.cobro_estado_id as cobroCita'
        ])->join('ordens as o','o.id','cobros.orden_id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p','p.id','cobros.paciente_id')
            ->join('tipordens as to','to.id','o.Tiporden_id')
            ->join('users as u','u.id','o.Usuario_id')
            ->where('p.Num_Doc',$identificacion)
            ->where('cobros.estado_id',4)
            ->orWhere('cp.cobro_estado_id',4)
            ->where('p.Num_Doc',$identificacion)
            ->get();
        return response()->json($cobros, 200);
    }
    public function generate(Request $request,Cobro $cobro){
        $response['data'] = [];
        $response['message'] = "Error en el cobro, intente de nuevo";
        $response['status'] = 400;
        $idOrden = $cobro->orden_id;
        $paciente = Paciente::find($cobro->paciente_id);
        $result = true;
        if($request->actualizar){
            $result =   $cobro->update([
                'usuario_id'  => auth()->user()->id,
                'estado_id'   => 7,
                'tipo_cobro'  => $request->tipo_cobro,
                'numero_referencia_pago' => ($request->numeroReferencia?$request->numeroReferencia:null),
                'fecha_cobro' => date('Y-m-d')
            ]);
            $orden = Orden::find($cobro->orden_id);
            $citaPaciente = citapaciente::find($orden->Cita_id);
            $citaPaciente->cobro_estado_id = 7;
            $citaPaciente->save();
        }
        if($result){
            $detalleOrdensMedicamentos = Detaarticulorden::select([
                "detaarticulordens.*",
                "c.nombre"
            ])->join("codesumis as c","c.id","detaarticulordens.codesumi_id")
                ->where('detaarticulordens.Orden_id',$idOrden)
                ->whereIn('detaarticulordens.Estado_id',[1,7,37,36])->get();
            $detalleOrdensServicios = Cuporden::select([
                "cupordens.Cup_id as id",
                "cupordens.Cantidad as cantidad",
                "cupordens.Observacionorden as observacion",
                "c.Codigo as codigo",
                "c.Nombre as nombre",
                "cupordens.estado_id",
                "cupordens.Orden_id",
                "cupordens.Ubicacion_id",
                "cupordens.Estado_id"
            ])->join("cups as c","c.id","cupordens.Cup_id")
                ->where("cupordens.Orden_id",$idOrden)
                ->whereIn('cupordens.Estado_id',[1,7,37])->get();
            //$detalleOrdens = Detaarticulorden::where('Orden_id',$idOrden)->whereNotIn('estado_id',[36,37])->get();
            //$detalleOrdensEntidad = Detaarticulorden::where('Orden_id',$idOrden)->whereIn('estado_id',[36,37])->get();
            $data = [];
            $data['order_id'] = $idOrden;
            $data["identificacion"] = $paciente->Num_Doc;
            $data["Fecha_actual"] = date('Y-m-d');
            $data["medicamentos"] = $detalleOrdensMedicamentos;
            $data["servicios"] = $detalleOrdensServicios;
            $data["mensaje"] = "";
            //$data["noAutorizados"] = $detalleOrdensEntidad;
            $response['data'] = $data;
            $response['message'] = "Cobro Realizado!";
            $response['status'] = 200;
        }
        return response()->json([
            'data' => $response['data'],
            'message' => $response['message'],
        ], $response['status']);
    }
    public function getPDF(Request $request){
        $pdf = new ServicioMedimas();
        $pdf->generar($request->all(),false);
        return $pdf;
    }
    public function getPDFMedicamentos(Request $request){
        $pdf = new Medicamento();
        $pdf->generar($request->all(),false,$request->mensaje);
        return $pdf;
    }
    public function getAnexo3(Request $request)
    {
        $idOrden = null;
        if ($request->anexos["medicamentos"]) {
            $idOrden = $request->anexos["medicamentos"][0]["Orden_id"];
        } elseif ($request->anexos["servicios"]) {
            $idOrden = $request->anexos["servicios"][0]["Orden_id"];
        }
        if ($idOrden) {
            $orden = Orden::find($idOrden);
            $cita = citapaciente::find($orden->Cita_id);
            $paciente = Paciente::find($cita->Paciente_id);
            $cie10 = Cie10::select(['cie10s.Codigo_CIE10 as codigo', 'cie10s.Descripcion','cp.Esprimario as primario'])
                ->join('cie10pacientes as cp', 'cp.Cie10_id', 'cie10s.id')
                ->where('cp.Citapaciente_id',$cita->id)
                ->get();
            $data = [
                'paciente' => $paciente,
                'servicios' => $request->anexos["servicios"],
                'medicamentos' =>$request->anexos["medicamentos"],
                'citaPaciente' => $cita,
                'cie10s' =>$cie10,
                'ordenId' => $orden->id
            ];
            $pdf = PDF::loadView('pdf_anexo3', $data);
            return $pdf->download();
        }
        return false;
    }
    public function getPDFRecibo(Request $request){
        $pdf = new ReciboPago();
        $pdf->generar($request->all());
        return $pdf;
    }
    public function getHistoryCobroPaciente($identificacion){
        $this->recalcularCobros($identificacion);
        $cobros = Cobro::select([
            'cobros.*',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'to.nombre',
            DB::raw("CONCAT(u.name,' ',u.apellido) as usuario_ordena"),
            DB::raw("CONCAT(uc.name,' ',uc.apellido) as usuario_cobra"),
            'o.Fechaorden',
            'cp.Finalidad',
            'to.id as idTipo',
            'o.id as idOrden',
            'cp.cobro_estado_id as cobroCita'
        ])->join('ordens as o','o.id','cobros.orden_id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p','p.id','cobros.paciente_id')
            ->join('tipordens as to','to.id','o.Tiporden_id')
            ->join('users as u','u.id','o.Usuario_id')
            ->leftjoin('users as uc','uc.id','cobros.usuario_id')
            ->with(['orden.cupordens' => function ($query) {
                $query->select(
                    'Cupordens.id as id',
                    'Cupordens.created_at as FechaOrdenamiento',
                    'Cupordens.Cup_id as Cup',
                    'Cupordens.Cantidad as Cup_Cantidad',
                    'Cupordens.Orden_id as Orden_id',
                    'Cupordens.Observacionorden as observaciones',
                    'Cupordens.Estado_id as Estado_id',
                    'c.id as Cup_Id',
                    'c.Nombre as Cup_Nombre',
                    'c.Codigo as Cup_Codigo',
                    's.id as Sede_Id',
                    's.Nombre as Sede_Nombre',
                    's.Direccion as Sede_Direccion',
                    's.Telefono1 as Sede_Telefono'
                )
                    ->join('Cups as c', 'Cupordens.Cup_id', 'c.id')
                    ->leftJoin('sedeproveedores as s', 'Cupordens.Ubicacion_id',  DB::raw("CONVERT(VARCHAR, s.id)"))
                    ->whereIn('Cupordens.Estado_id',[1,7,36,37])
                    ->get();
            }])
            ->where('p.Num_Doc',$identificacion)
            ->get();
        return response()->json($cobros, 200);
    }

    public function recalcularCobros($documento){
        $entidad = new EntidadController();
        $paciente = Paciente::where('Num_Doc',$documento)->first();
        if(isset($paciente)){
            $citasPacientes = citapaciente::where('Paciente_id',$paciente->id)->get();
            foreach ($citasPacientes as $citaPaciente){
                $entidad->validarServicio($citaPaciente->id);
                $entidad->validarMedicamento($citaPaciente->id);
                $entidad->validarInterConsulta($citaPaciente->id);
            }
        }
    }

    public function validarNumeroReferencia($numeroReferencia){
        $referencias = Cobro::where('numero_referencia_pago',$numeroReferencia)->first();
        return response()->json($referencias?true:false, 200);

    }

    public function generarInforme(Request $request){
        $fechaActual = date("Y-m-d");
        $cobros = citapaciente::select([
            "c.fecha_cobro",
            DB::raw("CONCAT(u.name,' ',u.apellido) as Usuario"),
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            "p.Num_Doc as Identificacion",
            DB::raw("SUM(CASE WHEN o.Tiporden_id = 4 THEN c.valor ELSE 0 END) as valor_ordenes"),
            "c.valor_cita as valor_cita",
            "c.tipo_cobro",
            "c.numero_referencia_pago",
            ])->join("ordens as o" ,"cita_paciente.id" , "o.Cita_id")
            ->join("cobros as c" , "o.id ", "c.orden_id")
            ->join("users as u", "c.usuario_id" , "u.id")
            ->join("pacientes as p" , "cita_paciente.Paciente_id","p.id")
            ->where("c.estado_id",7)
            ->where('c.tipo_cobro','!=','n/a')
            ->groupBy("cita_paciente.id","u.name","u.apellido","c.valor_cita","c.fecha_cobro","p.Primer_Ape","p.Segundo_Ape","p.Primer_Nom","p.SegundoNom","c.tipo_cobro","c.numero_referencia_pago","p.Num_Doc");
        if($request->actual == "true"){
                $cobros->where('c.fecha_cobro',$fechaActual);
        }else{
                $cobros->whereBetween('c.fecha_cobro', [$request->fechaDesde,$request->fechaHasta]);
        }

        return (new FastExcel($cobros->get()))->download('file.xls');
    }
}
