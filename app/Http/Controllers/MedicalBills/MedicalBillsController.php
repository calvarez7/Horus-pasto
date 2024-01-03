<?php

namespace App\Http\Controllers\MedicalBills;

use App\Modelos\Ac;
use App\Modelos\Af;
use App\Modelos\Am;
use App\Modelos\Ap;
use App\Modelos\At;
use App\Modelos\glosas;
use App\Modelos\Prestadore;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Modelos\Email_cmedicas;
use App\Modelos\radicacionGlosa;
use App\Modelos\Adjuntos_general;
use App\Modelos\Asignado_cmedicas;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modelos\AdjuntoRelacionPago;
use App\Modelos\RadicacionSumiGlosa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class MedicalBillsController extends Controller
{
    public function permisos()
    {
        $permisos = Permission::select('id', 'name')
        ->where('Tipo_id', 35)
        ->get();

        return response()->json($permisos, 201);
    }

    public function assign(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'af_id' => 'required',
            'permiso_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        foreach ($request->af_id as $det) {
            $af[] = $det;
        }
        foreach ($request->permiso_id as $det) {
            $permiso[] = $det;
        }

        for ($a = 0; $a < count($af); $a++) {
            for ($p = 0; $p < count($permiso); $p++) {
                $asignado = new Asignado_cmedicas;
                $asignado->permission_id = $permiso[$p];
                $asignado->af_id = $af[$a];
                $asignado->save();
            }
        }

        return response()->json([
            'message' => 'Factura asignada con exito!',
        ], 200);
    }

    public function myAssigned(Request $request)
    {
        $permisos    = Auth::user()->permissions;
        $permiso_id = [];
        foreach ($permisos as $permiso) {
            array_push($permiso_id, $permiso->id);
        }

        $prestadores = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            'permissions.name as permisos',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas")
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'af.id')
            ->join('permissions','permissions.id','cm.permission_id')
            ->whereIn('cm.permission_id', $permiso_id)
            ->where('af.estado_id', null)
            ->where('pr.estado_id',7)
            ->groupBy('Prestadores.id','Prestadores.Nombre','permissions.name','Prestadores.Nit');

        return response()->json($prestadores->get(), 201);
    }

    public function count()
    {
        $permisos    = Auth::user()->permissions;
        $permiso_id = [];
        foreach ($permisos as $permiso) {
            array_push($permiso_id, $permiso->id);
        }

        $asignadas   = Asignado_cmedicas::join('afs as af', 'asignado_cmedicas.af_id', 'af.id')
            ->join('paq_rips as pr','pr.id','af.paq_id')
            ->whereIn('asignado_cmedicas.permission_id', $permiso_id)
            ->where('af.estado_id', null)
            ->where('pr.estado_id',7)
            ->distinct()
            ->count();

        $auditadas   = Af::where('estado_id', 19)->count();

        $prestador   = Af::join('paq_rips as pr','pr.id','afs.paq_id')
        ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
        ->join('Prestadores as p','p.id','sp.prestador_id')
        ->where('afs.estado_id', 43)
        ->where('p.id', Auth::user()->prestador_id)
            ->where('pr.estado_id',7)
            ->distinct()
        ->count();

        $auditoFinal   = Af::where('estado_id', 18)->count();

        return response()->json([
            'asignadas' => $asignadas,
            'auditadas'  => $auditadas,
            'prestador' => $prestador,
            'final' => $auditoFinal
        ], 200);
    }

    public function invoiceAssign($prestador)
    {
        $permisos    = Auth::user()->permissions;
        $permiso_id = [];
        foreach ($permisos as $permiso) {
            array_push($permiso_id, $permiso->id);
        }

        $afs = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'afs.id')
            ->whereIn('cm.permission_id', $permiso_id)
            ->where('p.id',$prestador)
            ->where('afs.estado_id', null)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio')
            ->get();

            foreach ($afs as $af) {
                $fecha1 = Carbon::now()->format('Y-m-d');
                $fecha2 = Carbon::parse($af->created_at->format('Y-m-d'));
                $diasDiferencia = $fecha2->diffInDays($fecha1);
                if ($diasDiferencia < 0){
                    $diasDiferencia = 0;
                }
                $af['diasHabiles'] = $diasDiferencia;
            }

        return response()->json($afs, 201);
    }

    public function invoiceService($af)
    {
        $glosas = glosas::select(
            'concepto',
            'descripcion',
            'codigo',
            'valor',
            'id'
        )->where('af_id',$af)->get();
//        $AC = Ac::select('acs.id as id_ac','pa.Num_Doc','acs.Fecha_Consulta','acs.Codigo_Consulta',
//        'acs.valorneto_Pagar','acs.Diagnostico_Principal', 'acs.US_id', 'acs.estado_id','gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','acs.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->leftjoin('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->where('acs.Af_id', $af)
//        ->get();

//        $AP = Ap::select('aps.id as id_ap','pa.Num_Doc', 'aps.Fecha_Procedimiento', 'aps.Numero_Autorizacion',
//        'aps.Codigo_Procedimiento', 'aps.Valor_Procedimiento', 'aps.US_id', 'aps.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','aps.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'aps.Af_id', 'af.id')
//        ->leftjoin('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->where('aps.Af_id', $af)
//        ->get();

//        $AT = At::select('ats.id as id_at','pa.Num_Doc', 'ats.Nombre_Servicio','ats.Cantidad',
//        'ats.Valor_Unitario', 'ats.Valor_Total', 'ats.US_id', 'ats.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','ats.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'ats.Af_id', 'af.id')
//        ->leftjoin('glosas as gl', 'ats.id', 'gl.at_id')
//        ->where('ats.Af_id', $af)
//        ->get();

//        $AM = Am::select('ams.id as id_am','pa.Num_Doc', 'cu.Cum_Validacion','cu.Producto',
//        'cu.Forma_Farmaceutica','ams.Numero_Unidades','ams.Valorunitario_Medicamento',
//        'ams.Valortotal_Medicamento', 'ams.US_id', 'ams.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','ams.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'ams.Af_id', 'af.id')
//        ->join('cums as cu','ams.Cum_id','cu.id')
//        ->leftjoin('glosas as gl', 'ams.id', 'gl.am_id')
//        ->where('ams.Af_id', $af)
//        ->get();

//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);
        return response()->json($glosas);
    }

    public function glosa(Request $request)
    {
//        if(isset($request->valor)){

//            if(isset($request->id_ac)){
//                $updateAc = Ac::where('id', $request->id_ac)
//                ->update([
//                    'estado_id' => 7
//                ]);
//
//                $existe = glosas::where('ac_id', $request->id_ac)->first();
//            }
//            if(isset($request->id_ap)){
//                $updateAp = Ap::where('id', $request->id_ap)
//                ->update([
//                    'estado_id' => 7
//                ]);
//
//                $existe = glosas::where('ap_id', $request->id_ap)->first();
//            }
//            if(isset($request->id_at)){
//                $updateAt = At::where('id', $request->id_at)
//                ->update([
//                    'estado_id' => 7
//                ]);
//
//                $existe = glosas::where('at_id', $request->id_at)->first();
//            }
//            if(isset($request->id_am)){
//                $updateAm = Am::where('id', $request->id_am)
//                ->update([
//                    'estado_id' => 7
//                ]);
//
//                $existe = glosas::where('am_id', $request->id_am)->first();
//            }

            if(isset($request->id)){
                $updateGlosa = glosas::where('id', $request->id)
                ->update([
                    'estado_id' => 4,
                    'User_id'   => auth()->user()->id,
//                    'ac_id'     => $request->id_ac,
//                    'ap_id'     => $request->id_ap,
//                    'at_id'     => $request->id_at,
//                    'am_id'     => $request->id_am,
//                    'us_id'     => $request->US_id,
                    'codigo'    => $request->codigo,
                    'concepto'  => $request->concepto,
                    'descripcion'   => $request->descripcion,
                    'valor'     => $request->valor,
                    'af_id'     => $request->af_id
                ]);
            }else{
                $glosa = new glosas;
                $glosa->estado_id = 4;
                $glosa->User_id = auth()->user()->id;
//                $glosa->ac_id = $request->id_ac;
//                $glosa->ap_id = $request->id_ap;
//                $glosa->at_id = $request->id_at;
//                $glosa->am_id = $request->id_am;
//                $glosa->us_id = $request->US_id;
                $glosa->codigo = $request->codigo;
                $glosa->concepto = $request->concepto;
                $glosa->descripcion = $request->descripcion;
                $glosa->valor = $request->valor;
                $glosa->af_id = $request->af_id;
                $glosa->save();
            }

            return response()->json([
                'message' => 'Glosa guardada con exito!',
            ], 200);

//        }

    }

    public function saveServicio(Request $request){

        $updateServicio = Af::where('id', $request->id)
        ->update([
            'servicio' => $request->servicio
        ]);

        return response()->json([
            'message' => 'Servicio actualizado con exito!',
        ], 200);

    }

    public function saveAuditoria(Request $request){

        $updateAf = Af::where('id', $request->af_id)
        ->update([
            'estado_id' => 19
        ]);

        return response()->json([
            'message' => 'Auditoria guardada con exito!',
        ], 200);

    }

    public function auditadas(){

        $prestadoresAuditadas = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas"),
            'ec.email as emailNotificacion'
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'af.id')
            ->leftjoin('email_cmedicas as ec','Prestadores.id','ec.prestador_id')
            ->where('af.estado_id', 19)
            ->where('pr.estado_id',7)
            ->groupBy('Prestadores.id','Prestadores.Nombre','Prestadores.Nit','ec.email');

        return response()->json($prestadoresAuditadas->get(), 201);

    }

    public function invoiceAuditadas($prestador)
    {
        $afsAuditadas = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'afs.id')
            ->where('p.id',$prestador)
            ->where('afs.estado_id', 19)
            ->where('pr.estado_id',7)
            ->get();

        return response()->json($afsAuditadas, 201);

    }

    public function invoiceServiceAuditadas($af)
    {

//        $AC = Ac::select('acs.id as id_ac','pa.Num_Doc','acs.Fecha_Consulta','acs.Codigo_Consulta',
//        'acs.valorneto_Pagar','acs.Diagnostico_Principal', 'acs.US_id', 'acs.estado_id','gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','acs.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->where('acs.Af_id', $af)
//        ->get();
//
//        $AP = Ap::select('aps.id as id_ap','pa.Num_Doc', 'aps.Fecha_Procedimiento', 'aps.Numero_Autorizacion',
//        'aps.Codigo_Procedimiento', 'aps.Valor_Procedimiento', 'aps.US_id', 'aps.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','aps.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'aps.Af_id', 'af.id')
//        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->where('aps.Af_id', $af)
//        ->get();
//
//        $AT = At::select('ats.id as id_at','pa.Num_Doc', 'ats.Nombre_Servicio','ats.Cantidad',
//        'ats.Valor_Unitario', 'ats.Valor_Total', 'ats.US_id', 'ats.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','ats.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'ats.Af_id', 'af.id')
//        ->join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->where('ats.Af_id', $af)
//        ->get();
//
//        $AM = Am::select('ams.id as id_am','pa.Num_Doc', 'cu.Cum_Validacion','cu.Producto',
//        'cu.Forma_Farmaceutica','ams.Numero_Unidades','ams.Valorunitario_Medicamento',
//        'ams.Valortotal_Medicamento', 'ams.US_id', 'ams.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo', 'gl.valor', 'gl.id as id_glosas')
//        ->join('us as usu','ams.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'ams.Af_id', 'af.id')
//        ->join('cums as cu','ams.Cum_id','cu.id')
//        ->join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->where('ams.Af_id', $af)
//        ->get();

//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);
        $glosas = glosas::select(
            'concepto',
            'descripcion',
            'codigo',
            'valor',
            'id'
        )->where('af_id',$af)->get();

        return response()->json($glosas);
    }

    public function devolverAuditoria(Request $request){

        $updateAf = Af::where('id', $request->af_id)
        ->update([
            'estado_id' => null
        ]);

        return response()->json([
            'message' => 'Auditoria devuelta con exito!',
        ], 200);

    }

    public function notifyPrestador(Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $prestadorEmail = Email_cmedicas::updateOrCreate(['prestador_id' => $request->prestador], ['email' => $request->email]);
        $prestador = $request->prestador;
        if($prestadorEmail){
            $afsAuditadas = Af::select(['afs.id'])
                ->join('paq_rips as pr','pr.id','afs.paq_id')
                ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
                ->join('Prestadores as p','p.id','sp.prestador_id')
                ->where('p.id', $request->prestador)
                ->where('afs.estado_id', 19)
                ->where('pr.estado_id',7)
                ->groupBy('afs.id')
                ->get();

            $afs_conglosa_id = [];
//            $af_ids = [];

            foreach ($afsAuditadas as $key) {
                $glosa = glosas::where('af_id',$key->id)->first();
                if($glosa){
                    $afs_conglosa_id[] = $glosa->af_id;
                }
                Af::where('id', $key->id)
                ->update([
                    'estado_id' => 13
                ]);
            }

//            $updateAf = Af::whereIn('id', $af_ids)
//                ->update([
//                    'estado_id' => 13
//                ]);


//            $AC = Ac::join('glosas as gl', 'acs.id', 'gl.ac_id')
//                ->whereIn('acs.Af_id', $af_ids)
//                ->get();
//
//            foreach ($AC as $key) {
//                $afs_conglosa_id[] = $key->Af_id;
//            }
//
//            $AP = Ap::join('glosas as gl', 'aps.id', 'gl.ap_id')
//                ->whereIn('aps.Af_id', $af_ids)
//                ->get();
//
//            foreach ($AP as $key) {
//                $afs_conglosa_id[] = $key->Af_id;
//            }
//
//            $AT = At::join('glosas as gl', 'ats.id', 'gl.at_id')
//                ->whereIn('ats.Af_id', $af_ids)
//                ->get();
//
//            foreach ($AT as $key) {
//                $afs_conglosa_id[] = $key->Af_id;
//            }
//
//            $AM = Am::join('glosas as gl', 'ams.id', 'gl.am_id')
//                ->whereIn('ams.Af_id', $af_ids)
//                ->get();
//
//            foreach ($AM as $key) {
//                $afs_conglosa_id[] = $key->Af_id;
//            }

            $afs = array_unique($afs_conglosa_id);

            if(count($afs) > 0){
                foreach ($afs as $af){
                    $updateAf = Af::where('id', $af)
                    ->update([
                        'estado_id' => 43,
                        'fecha_notificacion_prestador' => date('Y-m-d H:i:s')
                    ]);
                }

                $email = Mail::send('email_cmedicas', ['email' => $prestadorEmail],
                    function ($message) use ($prestadorEmail) {
                        $message->to($prestadorEmail->email);
                        $message->subject('Cuentas Medicas');
                    });

                return response()->json([
                    'message' => 'El prestador fue notificado con exito!',
                ], 201);

            }else{
                return response()->json([
                    'message' => 'No habia glosas por notificar, proceso guardado con exito!',
                ], 201);
            }

        }else{
            return response()->json([
                'message' => 'El prestador no tiene un email parametrizado!',
            ], 401);
        }

    }

    public function invoicePrestadores()
    {
        $afsPrestador = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio',
            'p.Nit'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'afs.id')
            ->where('p.id', Auth::user()->prestador_id)
            ->where('afs.estado_id', 43)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio','p.Nit')
            ->get();

        return response()->json($afsPrestador, 201);

    }

    public function invoiceServiceprestador($af)
    {
        $glosas = glosas::select([
            'glosas.concepto', 'glosas.descripcion', 'glosas.codigo as codglosa', 'glosas.valor', 'glosas.id as id_glosas', 'rg.codigo',
            'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
            'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo'
        ])->leftjoin('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
            ->where('glosas.Af_id', $af)
            ->get();
//        $AC = Ac::select('acs.id as id_ac','pa.Num_Doc','acs.Fecha_Consulta','acs.Codigo_Consulta',
//        'acs.valorneto_Pagar','acs.Diagnostico_Principal', 'acs.US_id', 'acs.estado_id','gl.concepto',
//        'gl.descripcion', 'gl.codigo as codglosa', 'gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo')
//        ->join('us as usu','acs.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->leftjoin('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->where('acs.Af_id', $af)
//        ->get();
//
//        $AP = Ap::select('aps.id as id_ap','pa.Num_Doc', 'aps.Fecha_Procedimiento', 'aps.Numero_Autorizacion',
//        'aps.Codigo_Procedimiento', 'aps.Valor_Procedimiento', 'aps.US_id', 'aps.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo as codglosa', 'gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo')
//        ->join('us as usu','aps.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'aps.Af_id', 'af.id')
//        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->leftjoin('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->where('aps.Af_id', $af)
//        ->get();
//
//        $AT = At::select('ats.id as id_at','pa.Num_Doc', 'ats.Nombre_Servicio','ats.Cantidad',
//        'ats.Valor_Unitario', 'ats.Valor_Total', 'ats.US_id', 'ats.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo as codglosa', 'gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo')
//        ->join('us as usu','ats.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'ats.Af_id', 'af.id')
//        ->join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->leftjoin('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->where('ats.Af_id', $af)
//        ->get();
//
//        $AM = Am::select('ams.id as id_am','pa.Num_Doc', 'cu.Cum_Validacion','cu.Producto',
//        'cu.Forma_Farmaceutica','ams.Numero_Unidades','ams.Valorunitario_Medicamento',
//        'ams.Valortotal_Medicamento', 'ams.US_id', 'ams.estado_id', 'gl.concepto',
//        'gl.descripcion', 'gl.codigo as codglosa', 'gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo')
//        ->join('us as usu','ams.US_id','usu.id')
//        ->join('pacientes as pa','usu.Paciente_id','pa.id')
//        ->join('afs as af', 'ams.Af_id', 'af.id')
//        ->join('cums as cu','ams.Cum_id','cu.id')
//        ->join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->leftjoin('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->where('ams.Af_id', $af)
//        ->get();
//
//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);
        return response()->json($glosas);
    }

    public function respuestaPrestador(Request $request){

        $existe = radicacionGlosa::where('glosa_id', $request->id_glosas)->first();

        if(isset($existe)){
            $existe->update([
                'codigo'    => ($request->codigo == null ? $existe->codigo : $request->codigo),
                'repuesta_prestador'   => ($request->descripcionPrestador == null ? $existe->repuesta_prestador : $request->descripcionPrestador),
                'valor_aceptado'     => ($request->valorAPrestador == null ? $existe->valor_aceptado : $request->valorAPrestador),
                'valor_no_aceptado'     => ($request->valorNPrestador == null ? $existe->valor_no_aceptado : $request->valorNPrestador)
            ]);
        }else{
            $respuesta = new radicacionGlosa;
            $respuesta->glosa_id = $request->id_glosas;
            $respuesta->estado_id = 1;
            $respuesta->prestador_id = Auth::user()->prestador_id;
            $respuesta->codigo = $request->codigo;
            $respuesta->repuesta_prestador = $request->descripcionPrestador;
            $respuesta->valor_aceptado = $request->valorAPrestador;
            $respuesta->valor_no_aceptado = $request->valorNPrestador;
            $respuesta->save();
        }

        return response()->json([
            'message' => 'Respuesta guardada con exito!',
        ], 201);

    }

    public function adjunto(Request $request)
    {
        if ($file = $request->file('adjunto')) {

            $existe = radicacionGlosa::where('glosa_id', $request->glosa_id)->first();

            if(isset($existe)){

                $name   = $file->getClientOriginalName();
                $pathdb = '/storage/adjuntosCMedicas/'.Auth::user()->prestador_id .'/'.$request->glosa_id.'g'.time().$name;
                $path   = '../storage/app/public/adjuntosCMedicas/'.Auth::user()->prestador_id;

                if ($file->move(public_path($path), $request->glosa_id.'g'.time().$name)) {
                    $adjunto = radicacionGlosa::where('glosa_id', $request->glosa_id)->update([
                        'archivo' => $pathdb
                    ]);

                    return response()->json([
                        'message' => 'Adjunto cargado con exito!'
                    ], 200);
                }

            }else{
                return response()->json([
                    'message' => 'Aun no existe una respuesta!'
                ], 401);
            }

        }
    }

    public function saveAuditoriaPrestador(Request $request){

        $updateAf = Af::where('id', $request->af_id)
        ->update([
            'estado_id' => 18
        ]);

        return response()->json([
            'message' => 'Respuesta guardada con exito!',
        ], 200);

    }

    public function auditoriaFinal(){

        $prestadoresAuditadas = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas")
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'af.id')
            ->where('af.estado_id', 18)
            ->where('pr.estado_id',7)
            ->groupBy('Prestadores.id','Prestadores.Nombre','Prestadores.Nit');

        return response()->json($prestadoresAuditadas->get(), 201);

    }

    public function invoiceAuditoriaFinal($prestador)
    {
        $afsAuditadas = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'afs.id')
            ->where('p.id',$prestador)
            ->where('afs.estado_id', 18)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio')
            ->get();

        return response()->json($afsAuditadas, 201);

    }

    public function invoiceServiceAuditoriaFinal($af)
    {

        $glosas = glosas::select([
            'glosas.valor', 'glosas.id as id_glosas', 'rg.codigo',
            'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
            'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
            'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
            'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi'
        ])->join('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
        ->leftjoin('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
        ->where('glosas.af_id', $af)
        ->get();

//        $AC = Ac::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->leftjoin('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('acs.Af_id', $af)
//        ->get();
//
//        $AP = Ap::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->leftjoin('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('aps.Af_id', $af)
//        ->get();
//
//        $AT = At::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->leftjoin('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ats.Af_id', $af)
//        ->get();
//
//        $AM = Am::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->leftjoin('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ams.Af_id', $af)
//        ->get();
//
//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);

        return response()->json($glosas);
    }

    public function respuestaSumi(Request $request){

        $existe = RadicacionSumiGlosa::where('glosa_id', $request->glosa['id_glosas'])->first();

        if($request->tipo == 'Conciliar'){
            $estado = 1;
        }else{
            $estado = 2;
        }

        if(isset($existe)){
            $existe->update([
                'user_id' => Auth::user()->id,
                'estado_id' => $estado,
                'repuesta_sumimedical'   => ($request->glosa['descripcionSumi'] == null ? $existe->repuesta_sumimedical : $request->glosa['descripcionSumi']),
                'valor_aceptado'     => ($request->glosa['valorASumi'] == null ? $existe->valor_aceptado : $request->glosa['valorASumi']),
                'valor_no_aceptado'     => ($request->glosa['valorNSumi'] == null ? $existe->valor_no_aceptado : $request->glosa['valorNSumi'])
            ]);
        }else{
            $respuesta = new RadicacionSumiGlosa;
            $respuesta->glosa_id = $request->glosa['id_glosas'];
            $respuesta->estado_id = $estado;
            $respuesta->user_id = Auth::user()->id;
            $respuesta->repuesta_sumimedical = $request->glosa['descripcionSumi'];
            $respuesta->valor_aceptado = $request->glosa['valorASumi'];
            $respuesta->valor_no_aceptado = $request->glosa['valorNSumi'];
            $respuesta->save();
        }

        return response()->json([
            'message' => 'Respuesta guardada con exito!',
        ], 201);

    }

    public function saveAuditoriaFinal(Request $request){

        $encontro = false;

        $result = glosas::join('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
        ->join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
        ->where('glosas.af_id', $request->af_id)
        ->where('rs.estado_id', 1)
        ->count();

//        $AC = Ac::join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('acs.Af_id', $request->af_id)
//        ->where('rs.estado_id', 1)
//        ->count();
//
//        $AP = Ap::join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('aps.Af_id', $request->af_id)
//        ->where('rs.estado_id', 1)
//        ->count();
//
//        $AT = At::join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ats.Af_id', $request->af_id)
//        ->where('rs.estado_id', 1)
//        ->count();
//
//        $AM = Am::join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ams.Af_id', $request->af_id)
//        ->where('rs.estado_id', 1)
//        ->count();

        if($result > 0) {
            $encontro = true;
        }

        if($encontro == true){

            $updateAf = Af::where('id', $request->af_id)
            ->update([
                'estado_id' => 25
            ]);

            return response()->json([
                'message' => 'Factura en proceso de conciliación!',
            ], 201);

        }else{

            $updateAf = Af::where('id', $request->af_id)
            ->update([
                'estado_id' => 34
            ]);

            return response()->json([
                'message' => 'Factura cerrada con exito!',
            ], 201);
        }

    }

    public function facturasFinalizadas(){

        $facturas = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas")
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->where('af.estado_id', 25)
            ->where('pr.estado_id',7)
            ->groupBy('Prestadores.id','Prestadores.Nombre','Prestadores.Nit');

        return response()->json($facturas->get(), 201);

    }

    public function invoiceConciliacion($prestador)
    {
        $facturasConciliacion = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('p.id',$prestador)
            ->where('afs.estado_id', 25)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio')
            ->get();

        return response()->json($facturasConciliacion, 201);

    }

    public function invoiceServiceConciliacion($af)
    {
        $glosas = glosas::select([
            'glosas.valor', 'glosas.id as id_glosas', 'rg.codigo',
        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi'
        ])->join('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
        ->join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
        ->where('glosas.af_id', $af)
        ->where('rs.estado_id', 1)
        ->get();


//        $AC = Ac::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('acs.Af_id', $af)
//        ->where('rs.estado_id', 1)
//        ->get();
//
//        $AP = Ap::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('aps.Af_id', $af)
//        ->where('rs.estado_id', 1)
//        ->get();
//
//        $AT = At::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ats.Af_id', $af)
//        ->where('rs.estado_id', 1)
//        ->get();
//
//        $AM = Am::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ams.Af_id', $af)
//        ->where('rs.estado_id', 1)
//        ->get();
//
//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);
        return response()->json($glosas);
    }

    public function showPersisteSumi(Request $request){

        $valorPersisten = [];
        $id_glosas = [];

        $AC = Ac::select('gl.id as glosa_id', 'rs.valor_no_aceptado as valorPersiste',)
        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('acs.Af_id', $request->af_id)
        ->where('rs.estado_id', 1)
        ->get();

        foreach ($AC as $key) {
            $valorPersisten[] = $key->valorPersiste;
            $id_glosas[] = $key->glosa_id;
        }

        $AP = Ap::select('gl.id as glosa_id', 'rs.valor_no_aceptado as valorPersiste')
        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('aps.Af_id', $request->af_id)
        ->where('rs.estado_id', 1)
        ->get();

        foreach ($AP as $key) {
            $valorPersisten[] = $key->valorPersiste;
            $id_glosas[] = $key->glosa_id;
        }

        $AT = At::select('gl.id as glosa_id','rs.valor_no_aceptado as valorPersiste')
        ->join('glosas as gl', 'ats.id', 'gl.at_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('ats.Af_id', $request->af_id)
        ->where('rs.estado_id', 1)
        ->get();

        foreach ($AT as $key) {
            $valorPersisten[] = $key->valorPersiste;
            $id_glosas[] = $key->glosa_id;
        }

        $AM = Am::select('gl.id as glosa_id','rs.valor_no_aceptado as valorPersiste')
        ->join('glosas as gl', 'ams.id', 'gl.am_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('ams.Af_id', $request->af_id)
        ->where('rs.estado_id', 1)
        ->get();

        foreach ($AM as $key) {
            $valorPersisten[] = $key->valorPersiste;
            $id_glosas[] = $key->glosa_id;
        }

        $total = array_sum($valorPersisten);

        return response()->json([
            'Total' => $total,
            'glosa_id' => $id_glosas,
            'af_id' => $request->af_id
        ], 201);
    }

    public function saveConciliacion(Request $request){

        if($request->excel == null){
            return response()->json([
                'message' => '¡No adjunto ningun Excel!'
            ], 400);
        }else{

            $existe = [];
            $msg = '';

            (new FastExcel)->import($request->excel, function ($line) use (&$existe,&$msg,&$request) {

                if( strlen($line["GLOSA_ID"]) <= 0 || strlen($line["ACEPTA_IPS"]) <= 0 || strlen($line["LEVANTA_SUMI"]) <= 0
                || strlen($line["NO_ACUERDO"]) <= 0 ){

                    $msg = 'Hay campos obligatorios vacios, revisa: ( glosa_id, acepta_ips, levanta_sumi, no_acuerdo )';

                }else if($line["NIT"] != $request->nit_prestador){

                    $msg = 'Esta intentando cargar un Excel con un Nit diferente al seleccionado '. $request->nit_prestador;

                }else{

                    $data["glosa_id"] = $line["GLOSA_ID"];
                    $data["acepta_ips"] = $line["ACEPTA_IPS"];
                    $data["levanta_sumi"] = $line["LEVANTA_SUMI"];
                    $data["no_acuerdo"] = $line["NO_ACUERDO"];
                    $existe[] = $data;

                }

            });

            if($msg !== ''){

                return response()->json([
                    'message' => $msg
                ], 400);

            }else{

                $afs_id = [];

                foreach($existe as $e){

                    $rsumiglosa = RadicacionSumiGlosa::where('glosa_id', $e['glosa_id'])
                    ->update([
                        'acepta_ips' => $e['acepta_ips'],
                        'levanta_sumi' => $e['levanta_sumi'],
                        'no_acuerdo' => $e['no_acuerdo'],
                        'estado_id'  => 24
                    ]);

                    $rsumiglosaEstado = RadicacionSumiGlosa::where('glosa_id', $e['glosa_id'])
                    ->where('estado_id', 24)
                    ->where(DB::raw("CONVERT(INT, no_acuerdo)"), 0)
                    ->update([
                        'estado_id' => 2
                    ]);

                    $glosas = glosas::where('id', $e['glosa_id'])->first();
                    $afs_id[] = $glosas->af_id;

                }

                $afs = array_unique($afs_id);

                foreach($afs as $af){

                    $countglosas = glosas::where('af_id', $af)->count();
                    $countglosasEstado = glosas::join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
                    ->where('glosas.af_id', $af)
                    ->where('rs.estado_id', 2)
                    ->count();

                    if($countglosas == $countglosasEstado){
                        $updateAf = Af::where('id', $af)->update(['estado_id' => 34]);
                    }else {
                        $updateAf = Af::where('id', $af)->update(['estado_id' => 14]);
                    }

                }

                if ($file = $request->file('adjunto')) {

                    $name   = $file->getClientOriginalName();
                    $pathdb = '/storage/actasCuentaMedicas/'.$request->prestador_id . '/'. time() . $name;
                    $path   = '../storage/app/public/actasCuentaMedicas/'.$request->prestador_id;
                    if ($file->move($path, time().$name)) {
                        $covid = Adjuntos_general::create([
                            'tipo_id' => 35,
                            'user_id' => Auth::user()->id,
                            'prestador_id' => $request->prestador_id,
                            'nombre' =>  $name,
                            'ruta' =>  $pathdb
                        ]);
                    }
                }

                return response()->json([
                    'message' => 'Conciliación guardada con exito!'
                ], 201);

            }

        }

    }

    public function facturasCerradas(){

        $facturas = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas")
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->where('af.estado_id', 34)
            ->where('pr.estado_id',7)
            ->groupBy('Prestadores.id','Prestadores.Nombre','Prestadores.Nit');

        return response()->json($facturas->get(), 201);

    }

    public function invoiceCerradas($prestador){

        $facturasCerradas = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('p.id',$prestador)
            ->where('afs.estado_id', 34)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio')
            ->get();

        return response()->json($facturasCerradas, 201);

    }

    public function invoiceServiceCerradas($af)
    {

        $glosas = glosas::select([
            'glosas.valor', 'glosas.id as id_glosas', 'rg.codigo',
        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi'
        ])->join('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
        ->join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
        ->where('glosas.af_id', $af)
        ->where('rs.estado_id', 2)
        ->get();

//        $AC = Ac::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('acs.Af_id', $af)
//        ->where('rs.estado_id', 2)
//        ->get();
//
//        $AP = Ap::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('aps.Af_id', $af)
//        ->where('rs.estado_id', 2)
//        ->get();
//
//        $AT = At::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ats.Af_id', $af)
//        ->where('rs.estado_id', 2)
//        ->get();
//
//        $AM = Am::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ams.Af_id', $af)
//        ->where('rs.estado_id', 2)
//        ->get();
//
//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);
        return response()->json($glosas);
    }

    public function invoicePrestadoresConciliacion()
    {
        $afsPrestadorConciliacion = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio',
            'p.Nit'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'afs.id')
            ->where('p.id', Auth::user()->prestador_id)
            ->where('afs.estado_id', 25)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio','p.Nit')
            ->get();

        return response()->json($afsPrestadorConciliacion, 201);

    }

    public function invoicePrestadoresCerradas(){

        $afsPrestadorCerradas = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio',
            'p.Nit'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->join('asignado_cmedicas as cm', 'cm.af_id', 'afs.id')
            ->where('p.id', Auth::user()->prestador_id)
            ->where('afs.estado_id', 34)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio','p.Nit')
            ->get();

        return response()->json($afsPrestadorCerradas, 201);

    }

    public function informe(Request $request){

        if($request->tipo == 'Conciliación'){

            $afs = Af::select(['afs.id'])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('p.id', $request->prestador_id)
            ->where('afs.estado_id', 25)
                ->where('pr.estado_id',7)
                ->groupBy('afs.id')
                ->get();

            $af_id = [];
            foreach ($afs as $af) {
                $af_id[] = $af->id;
            }

            $informe = glosas::select(['p.Nit as NIT', 'P.Nombre as IPS', 'af.numero_factura as NUMERO_FACTURA',
            'af.valor_Neto as VALOR_FACTURA','glosas.id as GLOSA_ID', 'glosas.descripcion as DESCRIPCION_GLOSA',
            'rs.valor_no_aceptado as GLOSA_PERSISTE', 'rs.acepta_ips as ACEPTA_IPS', 'rs.levanta_sumi as LEVANTA_SUMI',
            'rs.no_acuerdo as NO_ACUERDO'])
            ->join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
            ->join('afs as af', 'glosas.af_id', 'af.id')
            ->join('paq_rips as pr','pr.id','af.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('glosas.created_at','>=',$request->fechaDesde)
            ->where('glosas.created_at','<=',$request->fechaHasta.' 23:59:59')
            ->whereIn('glosas.af_id', $af_id)
            ->where('rs.estado_id', 1)
                ->where('pr.estado_id',7)
                ->groupBy('p.Nit', 'P.Nombre', 'af.numero_factura',
                    'af.valor_Neto','glosas.id', 'glosas.descripcion',
                    'rs.valor_no_aceptado', 'rs.acepta_ips', 'rs.levanta_sumi',
                    'rs.no_acuerdo')
                ->get();

        }else if($request->tipo == 'Contabilidad'){

            $fechaInicio = $request->fechaDesde;
            $fechaFinal = $request->fechaHasta;

            $glosas = glosas::select(['p.Nit as NIT', 'P.Nombre as IPS', 'af.numero_factura as NUMERO_FACTURA',
            'af.fechaexpedicion_factura as FECHA_FACTURA', 'af.valor_Neto as VALOR_FACTURA', 'af.created_at as FECHA_RADICACION',
            'af.codigo_entidad as CONVENIO', 'af.updated_at as FECHA_AUDITORIA',
            DB::raw("SUM(CONVERT(INT, glosas.valor)) as VALOR_GLOSA"), DB::raw("SUM(CONVERT(INT, rs.valor_aceptado)) as VALOR_ACEPTADO")])
            ->leftjoin('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
            ->join('afs as af', 'glosas.af_id', 'af.id')
            ->join('paq_rips as pr','pr.id','af.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->whereIn("glosas.af_id",function ($query) use($fechaInicio, $fechaFinal) {
                $query->select('id')
                    ->from('afs')
                    ->where('created_at','>=',$fechaInicio)
                    ->where('created_at','<=',$fechaFinal.' 23:59:59')
                    ->whereIn('estado_id', [19,18,25,14,34,43]);
            })
                ->where('pr.estado_id',7)
                ->groupBy('p.Nit', 'P.Nombre', 'af.numero_factura', 'af.fechaexpedicion_factura', 'af.valor_Neto',
            'af.created_at', 'af.codigo_entidad', 'af.updated_at')
            ->get();

            $singlosa = Af::select(['p.Nit as NIT', 'P.Nombre as IPS', 'afs.numero_factura as NUMERO_FACTURA',
            'afs.fechaexpedicion_factura as FECHA_FACTURA', 'afs.valor_Neto as VALOR_FACTURA', 'afs.created_at as FECHA_RADICACION',
            'afs.codigo_entidad as CONVENIO', 'afs.updated_at as FECHA_AUDITORIA'])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('afs.created_at','>=',$fechaInicio)
            ->where('afs.created_at','<=',$fechaFinal.' 23:59:59')
            ->where('afs.estado_id', 13)
                ->where('pr.estado_id',7)
                ->groupBy('p.Nit', 'P.Nombre', 'afs.numero_factura', 'afs.fechaexpedicion_factura', 'afs.valor_Neto',
            'afs.created_at', 'afs.codigo_entidad', 'afs.updated_at')
            ->get();

            $ar0 = $glosas->toArray();
            $ar1 = $singlosa->toArray();
            $informe = array_merge($ar0,$ar1);

        }else if($request->tipo == 'Trazabilidad'){

            $fechaInicio = $request->fechaDesde;
            $fechaFinal = $request->fechaHasta;

            $glosas = glosas::select([
                'p.Nit as NIT',
                'af.numero_factura as NUMERO_FACTURA',
                'af.fechaexpedicion_factura as FECHA_FACTURA',
                'af.valor_Neto as VALOR_FACTURA',
                'af.created_at as FECHA_RADICACION',
//                'glosas.id as GLOSA_ID',
                'glosas.valor as GLOSA_INICIAL',
                'glosas.created_at as FECHA_AUDITORIA',
                'glosas.codigo as CODIGO_GLOSA',
                'glosas.descripcion as DESCRIPCION_GLOSA',
                'rg.codigo as COD_RTA',
                'rg.created_at as FECHA_RTA_IPS',
                'rg.repuesta_prestador as DESCRIPCION_RTA_IPS',
                'rg.valor_aceptado as IPS_ACEPTA',
                'rg.valor_no_aceptado as IPS_NO_ACEPTA',
                'rs.created_at as FECHA_RTA_SUMI',
                'rs.repuesta_sumimedical as DESCRIPCION_RTA_SUMI',
                'rs.valor_aceptado as SUMI_ACEPTA',
                'rs.valor_no_aceptado as SUMI_PERSISTE',
                'af.fecha_conciliacion as FECHA_CONCILIACION',
                'rs.acepta_ips as IPS_ACEPTA_2',
                'rs.levanta_sumi as SUMI_LEVANTA',
                'rs.no_acuerdo as NO_ACUERDO',
                'af.fecha_notificacion_prestador as FECHA_NOTIFICACION',
                'ec.email as CORREO_NOTIFICACION',
                'u.name as AUDITOR',
                'af.codigo_entidad as CONVENIO'
            ])
            ->leftjoin('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
            ->leftjoin('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
            ->join('afs as af', 'glosas.af_id', 'af.id')
            ->join('paq_rips as pr','pr.id','af.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
                ->join('estados as e','e.id','af.estado_id')
                ->join('asignado_cmedicas as ac','ac.af_id','af.id')
                ->leftjoin('email_cmedicas as ec','ec.prestador_id','p.id')
                ->join('users as u','u.id','glosas.User_id')
            ->whereIn("glosas.af_id",function ($query) use($fechaInicio, $fechaFinal) {
                $query->select('id')
                    ->from('afs')
                    ->where('created_at','>=',$fechaInicio)
                    ->where('created_at','<=',$fechaFinal.' 23:59:59')
                    ->whereIn('estado_id', [19,43,18,25,14,34]);
            })
                ->where('pr.estado_id',7)
                ->groupBy(
                    'p.Nit','af.numero_factura', 'af.fechaexpedicion_factura','af.valor_Neto','af.created_at','glosas.id','glosas.valor','glosas.created_at','glosas.codigo','glosas.descripcion','rg.codigo',
                    'rg.created_at','rg.repuesta_prestador','rg.valor_aceptado','rg.valor_no_aceptado','rs.created_at','rs.repuesta_sumimedical ','rs.valor_aceptado','rs.valor_no_aceptado',
                    'af.fecha_conciliacion','rs.acepta_ips','rs.levanta_sumi','rs.no_acuerdo','af.fecha_notificacion_prestador','ec.email','u.name','af.codigo_entidad')
                ->get();

            $singlosa = Af::select(['p.Nit as NIT', 'afs.numero_factura as NUMERO_FACTURA',
            'afs.fechaexpedicion_factura as FECHA_FACTURA', 'afs.valor_Neto as VALOR_FACTURA'])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('afs.created_at','>=',$fechaInicio)
            ->where('afs.created_at','<=',$fechaFinal.' 23:59:59')
            ->where('afs.estado_id', 13)
                ->where('pr.estado_id',7)
                ->groupBy('p.Nit', 'afs.numero_factura','afs.fechaexpedicion_factura', 'afs.valor_Neto')
                ->get();

            $ar0 = $glosas->toArray();
            $ar1 = $singlosa->toArray();
            $informe = array_merge($ar0,$ar1);

        }else if($request->tipo == 'No acuerdo'){

            $afs = Af::select(['afs.id'])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('p.id', $request->prestador_id)
            ->where('afs.estado_id', 14)
                ->where('pr.estado_id',7)
                ->groupBy('afs.id')
                ->get();

            $af_id = [];
            foreach ($afs as $af) {
                $af_id[] = $af->id;
            }

            $informe = glosas::select(['p.Nit as NIT', 'af.numero_factura as NUMERO_FACTURA', 'af.fechaexpedicion_factura as FECHA_FACTURA',
            'af.valor_Neto as VALOR_FACTURA','glosas.id as GLOSA_ID', 'glosas.valor as GLOSA_INICIAL','rg.valor_aceptado as IPS_ACEPTA',
            'rg.valor_no_aceptado as IPS_PERSISTE', 'rs.valor_aceptado as SUMI_ACEPTA', 'rs.valor_no_aceptado as SUMI_PERSISTE',
            'rs.acepta_ips as IPS_ACEPTA_2', 'rs.levanta_sumi as SUMI_LEVANTA', 'rs.no_acuerdo as NO_ACUERDO'])
            ->join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
            ->join('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
            ->join('afs as af', 'glosas.af_id', 'af.id')
            ->join('paq_rips as pr','pr.id','af.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('glosas.created_at','>=',$request->fechaDesde)
            ->where('glosas.created_at','<=',$request->fechaHasta.' 23:59:59')
            ->whereIn('glosas.af_id', $af_id)
            ->where('rs.estado_id', 24)
                ->where('pr.estado_id',7)
                ->groupBy('p.Nit', 'af.numero_factura', 'af.fechaexpedicion_factura',
                    'af.valor_Neto','glosas.id', 'glosas.valor','rg.valor_aceptado',
                    'rg.valor_no_aceptado', 'rs.valor_aceptado', 'rs.valor_no_aceptado',
                    'rs.acepta_ips', 'rs.levanta_sumi', 'rs.no_acuerdo')
                ->get();

        }else if($request->tipo == 'Glosas'){

            $afs = Af::select(['afs.id'])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('p.id', Auth::user()->prestador_id)
            ->whereIn('afs.estado_id', [43,18,25,14,34])
                ->where('pr.estado_id',7)
                ->groupBy('afs.id')
                ->get();

            $af_id = [];
            foreach ($afs as $af) {
                $af_id[] = $af->id;
            }

            $informe = glosas::select(['af.numero_factura as NUMERO_FACTURA', 'glosas.codigo as CODIGO_GLOSA',
            'glosas.descripcion as DESCRIPCION_GLOSA', 'glosas.valor as VALOR_GLOSA',
            'rg.codigo as COD_RTA', 'rg.created_at as FECHA_RTA_IPS',
            'rg.repuesta_prestador as DESCRIPCION_RTA_IPS','rg.valor_aceptado as IPS_ACEPTA', 'rg.valor_no_aceptado as IPS_NO_ACEPTA',
            'rs.created_at as FECHA_RTA_SUMI', 'rs.repuesta_sumimedical as DESCRIPCION_RTA_SUMI', 'rs.valor_aceptado as SUMI_ACEPTA',
            'rs.valor_no_aceptado as SUMI_PERSISTE'])
            ->leftjoin('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
            ->leftjoin('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
            ->join('afs as af', 'glosas.af_id', 'af.id')
            ->join('paq_rips as pr','pr.id','af.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('glosas.created_at','>=',$request->fechaDesde)
            ->where('glosas.created_at','<=',$request->fechaHasta.' 23:59:59')
            ->whereIn('glosas.af_id', $af_id)
                ->where('pr.estado_id',7)
                ->groupBy('af.numero_factura', 'glosas.codigo',
                    'glosas.descripcion', 'glosas.valor',
                    'rg.codigo', 'rg.created_at',
                    'rg.repuesta_prestador','rg.valor_aceptado', 'rg.valor_no_aceptado',
                    'rs.created_at', 'rs.repuesta_sumimedical', 'rs.valor_aceptado',
                    'rs.valor_no_aceptado')
                ->get();

        }

        return (new FastExcel($informe))->download('file.xls');
    }

    public function facturasConSaldo(){

        $facturasSaldo = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas")
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->where('af.estado_id', 14)
            ->where('pr.estado_id',7)
            ->groupBy('Prestadores.id','Prestadores.Nombre','Prestadores.Nit');

        return response()->json($facturasSaldo->get(), 201);

    }

    public function invoiceConciliacionSaldo($prestador)
    {
        $facturasConciliacionSaldo = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto',
            'afs.created_at',
            'afs.servicio'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('p.id',$prestador)
            ->where('afs.estado_id', 14)
            ->where('pr.estado_id',7)
            ->groupBy('afs.id','afs.numero_factura','afs.valor_Neto','afs.created_at','afs.servicio')
            ->get();

        return response()->json($facturasConciliacionSaldo, 201);

    }

    public function invoiceServiceConciliacionSaldo($af)
    {

        $glosas = glosas::select([
            'glosas.valor', 'glosas.id as id_glosas', 'rg.codigo',
            'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
            'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
            'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
            'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi'
        ])->join('radicacion_glosas as rg', 'glosas.id', 'rg.glosa_id')
            ->join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
            ->where('glosas.af_id', $af)
            ->where('rs.estado_id', 24)
            ->get();

//        $AC = Ac::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('acs.Af_id', $af)
//        ->where('rs.estado_id', 24)
//        ->get();
//
//        $AP = Ap::select('gl.valor', 'gl.id as id_glosas', 'rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('aps.Af_id', $af)
//        ->where('rs.estado_id', 24)
//        ->get();
//
//        $AT = At::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ats.id', 'gl.at_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ats.Af_id', $af)
//        ->where('rs.estado_id', 24)
//        ->get();
//
//        $AM = Am::select('gl.valor', 'gl.id as id_glosas','rg.codigo',
//        'rg.repuesta_prestador as descripcionPrestador', 'rg.valor_aceptado as valorAPrestador',
//        'rg.valor_no_aceptado as valorNPrestador', 'rg.estado_id as estado_respuesta', 'rg.archivo',
//        'rs.repuesta_sumimedical as descripcionSumi', 'rs.valor_aceptado as valorASumi',
//        'rs.valor_no_aceptado as valorNSumi', 'rs.estado_id as estado_sumi')
//        ->join('glosas as gl', 'ams.id', 'gl.am_id')
//        ->join('radicacion_glosas as rg', 'gl.id', 'rg.glosa_id')
//        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
//        ->where('ams.Af_id', $af)
//        ->where('rs.estado_id', 24)
//        ->get();
//
//        return response()->json([
//            'AC' => $AC,
//            'AP' => $AP,
//            'AT' => $AT,
//            'AM' => $AM
//        ], 201);
        return response()->json($glosas);
    }

    public function showPersisteSumiSaldo(Request $request){

        $valorPersisten = [];
        $id_glosas = [];

        $AC = Ac::select('gl.id as glosa_id', 'rs.no_acuerdo as valorNoAcuerdo',)
        ->join('glosas as gl', 'acs.id', 'gl.ac_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('acs.Af_id', $request->af_id)
        ->where('rs.estado_id', 24)
        ->get();

        foreach ($AC as $key) {
            $valorPersisten[] = $key->valorNoAcuerdo;
            $id_glosas[] = $key->glosa_id;
        }

        $AP = Ap::select('gl.id as glosa_id', 'rs.no_acuerdo as valorNoAcuerdo')
        ->join('glosas as gl', 'aps.id', 'gl.ap_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('aps.Af_id', $request->af_id)
        ->where('rs.estado_id', 24)
        ->get();

        foreach ($AP as $key) {
            $valorPersisten[] = $key->valorNoAcuerdo;
            $id_glosas[] = $key->glosa_id;
        }

        $AT = At::select('gl.id as glosa_id','rs.no_acuerdo as valorNoAcuerdo')
        ->join('glosas as gl', 'ats.id', 'gl.at_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('ats.Af_id', $request->af_id)
        ->where('rs.estado_id', 24)
        ->get();

        foreach ($AT as $key) {
            $valorPersisten[] = $key->valorNoAcuerdo;
            $id_glosas[] = $key->glosa_id;
        }

        $AM = Am::select('gl.id as glosa_id','rs.no_acuerdo as valorNoAcuerdo')
        ->join('glosas as gl', 'ams.id', 'gl.am_id')
        ->join('radicacion_sumi_glosas as rs', 'gl.id', 'rs.glosa_id')
        ->whereIn('ams.Af_id', $request->af_id)
        ->where('rs.estado_id', 24)
        ->get();

        foreach ($AM as $key) {
            $valorPersisten[] = $key->valorNoAcuerdo;
            $id_glosas[] = $key->glosa_id;
        }

        $total = array_sum($valorPersisten);

        return response()->json([
            'Total' => $total,
            'glosa_id' => $id_glosas,
            'af_id' => $request->af_id
        ], 201);
    }

    public function saveConciliacionSaldo(Request $request){

        if($request->excel == null){
            return response()->json([
                'message' => '¡No adjunto ningun Excel!'
            ], 400);
        }else{

            $existe = [];
            $msg = '';

            (new FastExcel)->import($request->excel, function ($line) use (&$existe,&$msg,&$request) {

                if( strlen($line["GLOSA_ID"]) <= 0 || strlen($line["IPS_ACEPTA_2"]) <= 0 || strlen($line["SUMI_LEVANTA"]) <= 0
                || strlen($line["NO_ACUERDO"]) <= 0 ){

                    $msg = 'Hay campos obligatorios vacios, revisa: ( glosa_id, ips_acepta_2, sumi_levanta, no_acuerdo )';

                }else if($line["NIT"] != $request->nit_prestador){

                    $msg = 'Esta intentando cargar un Excel con un Nit diferente al seleccionado '. $request->nit_prestador;

                }else{

                    $data["glosa_id"] = $line["GLOSA_ID"];
                    $data["acepta_ips"] = $line["IPS_ACEPTA_2"];
                    $data["levanta_sumi"] = $line["SUMI_LEVANTA"];
                    $data["no_acuerdo"] = $line["NO_ACUERDO"];
                    $existe[] = $data;

                }

            });

            if($msg !== ''){

                return response()->json([
                    'message' => $msg
                ], 400);

            }else{

                $afs_id = [];

                foreach($existe as $e){

                    $rsumiglosa = RadicacionSumiGlosa::where('glosa_id', $e['glosa_id'])
                    ->update([
                        'acepta_ips' => $e['acepta_ips'],
                        'levanta_sumi' => $e['levanta_sumi'],
                        'no_acuerdo' => $e['no_acuerdo']
                    ]);

                    $rsumiglosaEstado = RadicacionSumiGlosa::where('glosa_id', $e['glosa_id'])
                    ->where('estado_id', 24)
                    ->where(DB::raw("CONVERT(INT, no_acuerdo)"), 0)
                    ->update([
                        'estado_id' => 2
                    ]);

                    $glosas = glosas::where('id', $e['glosa_id'])->first();
                    $afs_id[] = $glosas->af_id;

                }

                $afs = array_unique($afs_id);

                foreach($afs as $af){

                    $countglosas = glosas::where('af_id', $af)->count();
                    $countglosasEstado = glosas::join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
                    ->where('glosas.af_id', $af)
                    ->where('rs.estado_id', 2)
                    ->count();

                    if($countglosas == $countglosasEstado){
                        $updateAf = Af::where('id', $af)->update(['estado_id' => 34,'fecha_conciliacion' => date('Y-m-d H:i:s')]);
                    }else {
                        $updateAf = Af::where('id', $af)->update(['estado_id' => 14,'fecha_conciliacion' => date('Y-m-d H:i:s')]);
                    }

                }

                if ($file = $request->file('adjunto')) {

                    $name   = $file->getClientOriginalName();
                    $pathdb = '/storage/actasCuentaMedicas/'.$request->prestador_id . '/'. time() . $name;
                    $path   = '../storage/app/public/actasCuentaMedicas/'.$request->prestador_id;
                    if ($file->move($path, time().$name)) {
                        $covid = Adjuntos_general::create([
                            'tipo_id' => 35,
                            'user_id' => Auth::user()->id,
                            'prestador_id' => $request->prestador_id,
                            'nombre' =>  $name,
                            'ruta' =>  $pathdb
                        ]);
                    }
                }

                return response()->json([
                    'message' => 'Conciliación guardada con exito!'
                ], 201);
            }
        }

    }

    public function actas(Request $request){

        $acta = Adjuntos_general::select(['id', 'nombre', 'ruta', 'created_at'])
        ->where('prestador_id', $request->prestador)
        ->where('tipo_id', 35)
        ->where('created_at','>=',$request->fechaDesde)
        ->where('created_at','<=',$request->fechaHasta.' 23:59:59')
        ->get();

        return response()->json($acta, 201);
    }

    public function permisosCmedicas(){

        $user  = auth()->user();
        $permisos = $user->permissions;

        return $permisos;

    }

    public function cargaPagos(Request $request){

        $files = $request->file('adjuntos');

        $i = 0;
        foreach ($files as $file) {
            $fileName[$i] = $file->getClientOriginalName();
            $filenametostore[$i] = '/adjuntosrelacionpagos/'.$request->prestador_id.'/'.time().'_'.$fileName[$i];
            Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

            $adjunto = new AdjuntoRelacionPago;
            $adjunto->nombre = $fileName[$i];
            $adjunto->ruta = $filenametostore[$i];
            $adjunto->fecha = $request->fecha;
            $adjunto->prestador_id = $request->prestador_id;
            $adjunto->estado_id = 1;
            $adjunto->save();

            $i++;
        }

        return response()->json([
            'message' => 'Adjunto de pago cargado con exito!'
        ], 201);

    }

    public function searchCargaPagos(Request $request){

        $relaciondepagos = AdjuntoRelacionPago::where('prestador_id', $request->prestador_id)
        ->where('fecha', $request->fecha)
        ->where('estado_id', 1)
        ->get();

        return response()->json($relaciondepagos, 201);

    }

    public function searchCargaPagosPrestador(Request $request){

        if(auth()->user()->prestador_id == null || auth()->user()->prestador_id == ''){
            return response()->json([
                'message' => 'No eres un prestador contacta con soporte si crees que es un error!'
            ], 401);
        }else{
            $relaciondepagos = AdjuntoRelacionPago::where('prestador_id', auth()->user()->prestador_id)
            ->where('fecha', $request->fecha)
            ->where('estado_id', 1)
            ->get();

            return response()->json($relaciondepagos, 201);
        }

    }

    public function deletePagosPrestador(AdjuntoRelacionPago $adjuntoPago){

        $adjuntoPago->update([
            'estado_id' => 2
        ]);

        return response()->json([
            'message' => 'Adjunto relación pagos eliminado con exito!'
        ], 201);

    }

    public function generarEstadoCuenta(Request $request){

        if(auth()->user()->prestador_id == null || auth()->user()->prestador_id == ''){
            return response()->json([
                'message' => 'No eres un prestador contacta con soporte si crees que es un error!'
            ], 401);
        }else{

            $prestador = Prestadore::where('id', auth()->user()->prestador_id)->first();

            $array = DB::select("exec dbo.PagosDinamica ?,?", [$prestador->Nit,$request->fecha]);
            $appointments = json_decode(json_encode($array), true);
            return (new FastExcel($appointments))->download('file.xls');
        }

    }

    public function saveConciliacionAdministrativa(Request $request){
        if($request->excel == null){
            return response()->json([
                'message' => '¡No adjunto ningun Excel!'
            ], 400);
        }else{

            $existe = [];
            $msg = '';

            (new FastExcel)->import($request->excel, function ($line) use (&$existe,&$msg,&$request) {

                if( strlen($line["GLOSA_ID"]) <= 0 || strlen($line["ACEPTA_IPS"]) <= 0 || strlen($line["LEVANTA_SUMI"]) <= 0
                    || strlen($line["NO_ACUERDO"]) <= 0 ){

                    $msg = 'Hay campos obligatorios vacios, revisa: ( glosa_id, acepta_ips, levanta_sumi, no_acuerdo )';

                }else if($line["NIT"] != $request->nit_prestador){

                    $msg = 'Esta intentando cargar un Excel con un Nit diferente al seleccionado '. $request->nit_prestador;

                }else{

                    $data["glosa_id"] = $line["GLOSA_ID"];
                    $data["acepta_ips"] = $line["ACEPTA_IPS"];
                    $data["levanta_sumi"] = $line["LEVANTA_SUMI"];
                    $data["no_acuerdo"] = $line["NO_ACUERDO"];
                    $existe[] = $data;

                }

            });

            if($msg !== ''){

                return response()->json([
                    'message' => $msg
                ], 400);

            }else{

                $afs_id = [];

                foreach($existe as $e){

                    $rsumiglosa = RadicacionSumiGlosa::where('glosa_id', $e['glosa_id'])
                        ->update([
                            'valor_no_aceptado' => $e['glosa_inicial'],
                            'acepta_ips' => $e['acepta_ips'],
                            'levanta_sumi' => $e['levanta_sumi'],
                            'no_acuerdo' => $e['no_acuerdo'],
                            'estado_id'  => 24
                        ]);

                    $rsumiglosaEstado = RadicacionSumiGlosa::where('glosa_id', $e['glosa_id'])
                        ->where('estado_id', 24)
                        ->where(DB::raw("CONVERT(INT, no_acuerdo)"), 0)
                        ->update([
                            'estado_id' => 2
                        ]);

                    $glosas = glosas::where('id', $e['glosa_id'])->first();
                    $afs_id[] = $glosas->af_id;

                }

                $afs = array_unique($afs_id);

                foreach($afs as $af){

                    $countglosas = glosas::where('af_id', $af)->count();
                    $countglosasEstado = glosas::join('radicacion_sumi_glosas as rs', 'glosas.id', 'rs.glosa_id')
                        ->where('glosas.af_id', $af)
                        ->where('rs.estado_id', 2)
                        ->count();

                    $updateAf = Af::where('id', $af)->update(['estado_id' => 34]);
                }


                return response()->json([
                    'message' => 'Conciliación guardada con exito!'
                ], 201);

            }

        }
    }
}
