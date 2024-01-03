<?php

namespace App\Http\Controllers\SeguimientoRed;

use App\Http\Controllers\Controller;
use App\Modelos\Ac;
use App\Modelos\AcTemp;
use App\Modelos\Af;
use App\Modelos\AfTemp;
use App\Modelos\Ah;
use App\Modelos\AhTemp;
use App\Modelos\Am;
use App\Modelos\AmTemp;
use App\Modelos\An;
use App\Modelos\AnTemp;
use App\Modelos\Ap;
use App\Modelos\ApTemp;
use App\Modelos\At;
use App\Modelos\AtTemp;
use App\Modelos\Au;
use App\Modelos\AuTemp;
use App\Modelos\Cie10;
use App\Modelos\Ct;
use App\Modelos\CtTemp;
use App\Modelos\Cum;
use App\Modelos\Cup;
use App\Modelos\CupEntidade;
use App\Modelos\Cuporden;
use App\Modelos\Departamento;
use App\Modelos\Municipio;
use App\Modelos\Paciente;
use App\Modelos\PaqRip;
use App\Modelos\PaqTemp;
use App\Modelos\Referencia;
use App\Modelos\Sedeproveedore;
use App\Modelos\Prestadore;
use App\Modelos\Us;
use App\Modelos\UsTemp;
use Illuminate\Http\Request;
use App\Http\Controllers\Entidades\EntidadController;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class RedController extends Controller
{

    public function getOportunidad(Request $request){
        $serviciosProveedores = Cuporden::select('s.Nombre as prestador','c.Codigo','c.Nombre','t.Manual','t.Tarifa','t.Ambito',
            DB::raw("(SUM(CASE WHEN cupordens.fecha_solicitada IS NULL THEN DATEDIFF(DAY,o.Fechaorden,getdate())
       ELSE DATEDIFF(DAY,o.Fechaorden,cupordens.fecha_solicitada)
       END)/count(c.id)) as oportunidad"))
            ->join('ordens as o' , 'cupordens.Orden_id' , 'o.id')
            ->join('cita_paciente as cp' , 'o.Cita_id' , 'cp.id')
            ->join('pacientes as p' , 'cp.Paciente_id' , 'p.id')
            ->join('cups as c' , 'cupordens.Cup_id' , 'c.id')
            ->join('tarifarios as t' ,'cupordens.Cup_id' , 't.Cup_id')
            ->join('contratos as c2' , 't.Contrato_id' , 'c2.id')
            ->join('sedeproveedores as s' , 'cupordens.ubicacion_id' , 's.id')
            ->where('p.entidad_id',3)
            ->where('t.Estado_id',1)
            ->where('c2.entidad_id',3)
        ->whereIn('c.id',$request->cups);
        if(isset($request->ips)){
            $serviciosProveedores->where('p.IPS',$request->ips);
        }
        if(isset($request->departamento)){
            $serviciosProveedores->where('p.Dane_Dpto',$request->departamento);
        }
        if(isset($request->municio)){
            $serviciosProveedores->where('p.Dane_Mpio',$request->municio);
        }
            //->whereNotNull('cupordens.cancelacion')
            $serviciosProveedores->groupBy('c.Codigo','c.Nombre','t.Manual','t.Tarifa','t.Ambito','cupordens.ubicacion_id','s.Nombre');

        return response()->json($serviciosProveedores->get()->toArray());
    }

    public function getOportunidadAuditoria(Request $request){
        $serviciosProveedores = Cuporden::select('s.Nombre as prestador','c.Codigo','c.Nombre','t.Manual','t.Tarifa','t.Ambito',
            DB::raw("(SUM(DATEDIFF(DAY,o.created_at,a.created_at))/count(c.id)) as oportunidad"))
            ->join('ordens as o' , 'cupordens.Orden_id' , 'o.id')
            ->join('cita_paciente as cp' , 'o.Cita_id' , 'cp.id')
            ->join('pacientes as p' , 'cp.Paciente_id' , 'p.id')
            ->join('cups as c' , 'cupordens.Cup_id' , 'c.id')
            ->join('tarifarios as t' ,'cupordens.Cup_id' , 't.Cup_id')
            ->join('contratos as c2' , 't.Contrato_id' , 'c2.id')
            ->join('sedeproveedores as s' , 'cupordens.ubicacion_id' , 's.id')
            ->join('autorizacions as a', 'cupordens.id' , 'a.Cuporden_id')
            ->where('p.entidad_id',3)
            ->where('t.Estado_id',1)
            ->where('c2.entidad_id',3)
            ->whereIn('c.id',$request->cups);
        if(isset($request->ips)){
            $serviciosProveedores->where('p.IPS',$request->ips);
        }
        if(isset($request->departamento)){
            $serviciosProveedores->where('p.Dane_Dpto',$request->departamento);
        }
        if(isset($request->municio)){
            $serviciosProveedores->where('p.Dane_Mpio',$request->municio);
        }
        //->whereNotNull('cupordens.cancelacion')
        $serviciosProveedores->groupBy('c.Codigo','c.Nombre','t.Manual','t.Tarifa','t.Ambito','cupordens.ubicacion_id','s.Nombre');

        return response()->json($serviciosProveedores->get()->toArray());
    }
}



