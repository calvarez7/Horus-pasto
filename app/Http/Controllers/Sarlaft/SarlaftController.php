<?php

namespace App\Http\Controllers\Sarlaft;

use App\Http\Controllers\Controller;
use App\Modelos\adjunto_revision;
use App\Modelos\adjunto_sarlaft;
use App\Modelos\revision_sarlaft;
use App\Modelos\sarlaft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class SarlaftController extends Controller
{
    public function all($tipo){
        $sarlaftInformacion = sarlaft::select('sarlafts.*','sarlafts.id as idSarlaft','users.name','users.apellido','users.cedula',
        'users.nit','municipios.nombre as municipio','departamentos.Nombre as departamento','representante_legals.*',
        'representante_legals.tipo_doc as tipo_documento','M.nombre as Municipio_repre','Mu.nombre as Municipio_nacimiento',
        'Mui.nombre as Municipio_recidencia','d.Nombre as deparamento_reci','socios.*','socios.tipo_doc as tipodoc_socio',
        'socios.num_doc as documento_socio','persona_expuestas.nacionalidad as nacionalidad_expuesta','persona_expuestas.razon_social as nombre_social',
        'persona_expuestas.cargo as cargo_social','persona_expuestas.relacion as relacion_expuesta','persona_expuestas.entidad as entidad_expuesta',
        'actividad_internacionals.*','declaracion_fondos.*','informacion_financieras.*')
        ->join ('users','sarlafts.user_id','users.id')
        ->leftjoin ('municipios', 'municipios.id','sarlafts.municipio_id')
        ->join ('representante_legals','representante_legals.sarlafs_id','sarlafts.id')
        ->leftjoin ('socios','socios.sarlafs_id','sarlafts.id')
        ->leftjoin ('persona_expuestas', 'persona_expuestas.sarlafs_id','sarlafts.id')
        ->join ('informacion_financieras','informacion_financieras.sarlafs_id','sarlafts.id')
        ->join ('actividad_internacionals','actividad_internacionals.sarlafs_id','sarlafts.id')
        ->join ('declaracion_fondos','declaracion_fondos.sarlafs_id','sarlafts.id')
        ->leftjoin('municipios as M','M.id','representante_legals.lugar_exp')
        ->join('municipios as Mu','Mu.id','representante_legals.lugar_nac')
        ->join('municipios as Mui','Mui.id','representante_legals.ciudad_reci')
        ->leftjoin ('departamentos','departamentos.id','sarlafts.departamento_id')
        ->join('departamentos as d','d.id','representante_legals.deparamento_reci')
        ->where('sarlafts.estado_id', 1)
        ->where('tipo_solicitud',$tipo)->get();
    return response()->json($sarlaftInformacion, 200);
    }
    public function revisionBuscar($estado){
        if($estado==13){
        $sarlaftRevision = sarlaft::select('sarlafts.*','sarlafts.id as idSarlaft','sarlafts.created_at as Fecharealizacion','users.name','users.apellido','users.cedula',
            'users.nit','municipios.nombre as municipio','departamentos.Nombre as departamento','representante_legals.*',
            'representante_legals.tipo_doc as tipo_documento','M.nombre as Municipio_repre','Mu.nombre as Municipio_nacimiento',
            'Mui.nombre as Municipio_recidencia','d.Nombre as deparamento_reci','socios.*','socios.tipo_doc as tipodoc_socio',
            'socios.num_doc as documento_socio','persona_expuestas.nacionalidad as nacionalidad_expuesta','persona_expuestas.razon_social as nombre_social',
            'persona_expuestas.cargo as cargo_social','persona_expuestas.relacion as relacion_expuesta','persona_expuestas.entidad as entidad_expuesta',
            'actividad_internacionals.*','declaracion_fondos.*','informacion_financieras.*')
            ->join ('users','sarlafts.user_id','users.id')
            ->leftjoin ('municipios', 'municipios.id','sarlafts.municipio_id')
            ->join ('representante_legals','representante_legals.sarlafs_id','sarlafts.id')
            ->leftjoin ('socios','socios.sarlafs_id','sarlafts.id')
            ->leftjoin ('persona_expuestas', 'persona_expuestas.sarlafs_id','sarlafts.id')
            ->join ('informacion_financieras','informacion_financieras.sarlafs_id','sarlafts.id')
            ->join ('actividad_internacionals','actividad_internacionals.sarlafs_id','sarlafts.id')
            ->join ('declaracion_fondos','declaracion_fondos.sarlafs_id','sarlafts.id')
            ->leftjoin('municipios as M','M.id','representante_legals.lugar_exp')
            ->join('municipios as Mu','Mu.id','representante_legals.lugar_nac')
            ->join('municipios as Mui','Mui.id','representante_legals.ciudad_reci')
            ->leftjoin ('departamentos','departamentos.id','sarlafts.departamento_id')
            ->join('departamentos as d','d.id','representante_legals.deparamento_reci')
            ->where('sarlafts.estado_id','!=',1)->get();
            return response()->json($sarlaftRevision, 200);
        }
        else if($estado==1){
            $date = Carbon::now();
            $menosunaño= $date->addYears(-1)->format('Y-m-d H:i:s');
            $sarlaftRevision = sarlaft::select('sarlafts.*','sarlafts.id as idSarlaft','sarlafts.created_at as Fecharealizacion','users.name','users.apellido','users.cedula',
                'users.nit','municipios.nombre as municipio','departamentos.Nombre as departamento','representante_legals.*',
                'representante_legals.tipo_doc as tipo_documento','M.nombre as Municipio_repre','Mu.nombre as Municipio_nacimiento',
                'Mui.nombre as Municipio_recidencia','d.Nombre as deparamento_reci','socios.*','socios.tipo_doc as tipodoc_socio',
                'socios.num_doc as documento_socio','persona_expuestas.nacionalidad as nacionalidad_expuesta','persona_expuestas.razon_social as nombre_social',
                'persona_expuestas.cargo as cargo_social','persona_expuestas.relacion as relacion_expuesta','persona_expuestas.entidad as entidad_expuesta',
                'actividad_internacionals.*','declaracion_fondos.*','informacion_financieras.*')
                ->join ('users','sarlafts.user_id','users.id')
                ->leftjoin ('municipios', 'municipios.id','sarlafts.municipio_id')
                ->join ('representante_legals','representante_legals.sarlafs_id','sarlafts.id')
                ->leftjoin ('socios','socios.sarlafs_id','sarlafts.id')
                ->leftjoin ('persona_expuestas', 'persona_expuestas.sarlafs_id','sarlafts.id')
                ->join ('informacion_financieras','informacion_financieras.sarlafs_id','sarlafts.id')
                ->join ('actividad_internacionals','actividad_internacionals.sarlafs_id','sarlafts.id')
                ->join ('declaracion_fondos','declaracion_fondos.sarlafs_id','sarlafts.id')
                ->leftjoin('municipios as M','M.id','representante_legals.lugar_exp')
                ->join('municipios as Mu','Mu.id','representante_legals.lugar_nac')
                ->join('municipios as Mui','Mui.id','representante_legals.ciudad_reci')
                ->leftjoin ('departamentos','departamentos.id','sarlafts.departamento_id')
                ->join('departamentos as d','d.id','representante_legals.deparamento_reci')
                ->where('sarlafts.estado_id',13)
                ->where('sarlafts.created_at','<=',$menosunaño)->get();
             return response()->json($sarlaftRevision, 200);
        }

    }
    public function adjuntosSarlafts($sarlaft_id){

    $sarlaftAdjuntos = adjunto_sarlaft::where('adjunto_sarlafts.sarlafs_id',$sarlaft_id)->get();
    return response()->json($sarlaftAdjuntos, 200);
    }
    public function guardarRevision(Request $request)
    {
        $revision = revision_sarlaft::create([
            'sarlafs_id'=> $request->sarlafs_id,
            'user_id' => Auth::user()->id,
            'revision' => $request->revision,
        ]);
        $revisiones=revision_sarlaft::where('sarlafs_id',$request->sarlafs_id)->get();

         return response()->json([
            'message' => 'informacion guardad con exito',
             'revisiones' => $revisiones,
             'idRevision' => $revision->id,
        ]);
    }
    public function updateRevision($idsarlaft){
        sarlaft::find($idsarlaft)->update(['estado_id'=>13]);
        return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);

    }
    public function envioCorreo($idsarlaft){
        $correo=sarlaft::select('sarlafts.id as idSarlaft','sarlafts.email_empresa','users.name','users.apellido','users.cedula')
            ->join('users','users.id','sarlafts.user_id')
            ->where('sarlafts.id',$idsarlaft)->first();
        $nombre=$correo->name;
        $apellido=$correo->apellido;
        $email=$correo->email_empresa;
            Mail::send('email_notificacionSarlaft',['Nombre' => $nombre,'Apellido'=>$apellido],function ($message) use ($email,$nombre,$apellido) {
                $message->to($email, $nombre .' '.$apellido)->subject('Recordatorio de actualizacion de datos');
            });

        return response()->json([
            'message' => 'Informacion enviada con exito'
        ], 200);

    }
    public function consultarRevision($sarlafts_id){
        $revision=revision_sarlaft::Select('revision_sarlafts.id','revision_sarlafts.created_at as FechaRevision','revision_sarlafts.revision','users.name','users.apellido')
        ->join('users','users.id','revision_sarlafts.user_id')
            ->with(['adjuntos'=>function ($query){
                $query->select('revision_sarlafts_id','nombre','ruta')->get();
            }])
        ->where('sarlafs_id',$sarlafts_id)->get();
        return response()->json($revision, 200);
    }
    public function guardarAdjuntos($idRevision, Request $request){

        if($request->hasFile('adjuntoRevision')){

                $files = $request->file('adjuntoRevision');
                $i = 0;
                foreach ($files as $file) {
                    $path = '../storage/app/public/adjuntoRevision/' . $idRevision;
                    $paths = '/storage/adjuntoRevision/' . $idRevision . '/' . time() . $file->getClientOriginalName();
                    $file->move($path, time() . $file->getClientOriginalName());
                    $i++;

                    adjunto_revision::create([
                        'revision_sarlafts_id' => $idRevision,
                        'nombre' => $file->getClientOriginalName(),
                        'ruta' => $paths
                    ]);
                }
        }
    }
    public function adjuntosRevision($idRevision){
        $adjuntoRevision=adjunto_revision::where('revision_sarlafts_id',$idRevision)->get();
        return response()->json($adjuntoRevision, 200);

    }
    public function  updateNotifiacion($idsarlaft){
        sarlaft::find($idsarlaft)->update(['estado_id'=>7]);
        return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);

    }

}
