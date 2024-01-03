<?php
namespace App\Repositories;

use App\Modelos\citapaciente;
use App\Modelos\Codesumi;
use App\Modelos\Orden;
use Illuminate\Support\Facades\DB;

class OrdenRepository
{

    public function allOrdersByMonth($request){

        $query = Orden::select([
            'ordens.*',
            'user.name as User_Name',
            'user.apellido as User_LastName',
            'user.Firma as Medico_Firma',
            'cita_paciente.id as citaPaciente_id',
            'p.id as Paciente_id',
            'p.Departamento',
            'p.created_at as paciente_created',
            'p.Primer_Nom as Primer_Nom',
            'p.SegundoNom as Segundo_Nom',
            'p.Primer_Ape as Primer_Ape',
            'p.Segundo_Ape as Segundo_Ape',
            'p.Tipo_Doc as Tipo_Doc',
            'p.Num_Doc as Cedula',
            'p.Edad_Cumplida as Edad_Cumplida',
            'p.Sexo as Sexo',
            'p.IPS as IpsAtencion',
            'p.Direccion_Residencia as Direccion_Residencia',
            'p.Correo1 as Correo',
            'p.Telefono as Telefono',
            'p.Celular1 as Celular',
            'p.Tipo_Afiliado as Tipo_Afiliado',
            'p.Estado_Afiliado as Estado_Afiliado',
            'm.Nombre as Municipio',
            's.Nombre as Nombre_IPS',
            's.Direccion as Direccion_IPS',
            's.Telefono1 as Telefono_IPS',
            'cie10.Codigo_CIE10 as Codigo_CIE10',
            'cie10.Descripcion as Descripcion_CIE10',
            'sp.Nombre as nombrePrestador',
            'sp.Direccion as direccionPrestador',
            'pr.Nit as nitPrestador',
            'sp.Telefono1 as telefono1Prestador',
            'sp.Telefono2 as telefono2Prestador',
            'sp.Codigo_habilitacion as codigoHabilitacion',
            'mp.Nombre as municipioPrestador',
            'dp.Nombre as departamentoPrestador'
        ])
            ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
            ->join('Users as user', 'ordens.Usuario_id', 'user.id')
            ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
            ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->leftjoin('Municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
            ->leftJoin('sedeproveedores as s', 'p.IPS', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
            ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
            ->leftJoin('prestadores as pr','s.Prestador_id','pr.id')
            ->leftJoin('cupordens as cp','cp.Orden_id','ordens.id')
            ->leftJoin('sedeproveedores as sp','cp.Ubicacion_id', DB::raw("CONVERT(VARCHAR, sp.id)"))
            ->leftJoin('Municipios as mp','sp.Municipio_id',DB::raw("CONVERT(VARCHAR, m.id)"))
            ->leftJoin('departamentos as dp','mp.Departamento_id','dp.id')
            ->with(['detaarticulordens' => function ($query) use ($request) {
                $query->select(
                    'detaarticulordens.id as id',
                    'detaarticulordens.created_at as FechaOrdenamiento',
                    'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                    'detaarticulordens.codesumi_id as Codesumi_id',
                    'detaarticulordens.Via as Via',
                    'detaarticulordens.Unidadmedida as Unidad_Medida',
                    'detaarticulordens.Frecuencia as Frecuencia',
                    'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                    'detaarticulordens.Duracion as Duracion',
                    'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                    'detaarticulordens.Nummeses as Num_Meses',
                    'detaarticulordens.Observacion as Observacion',
                    'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                    'detaarticulordens.Orden_id as Orden_id',
                    'detaarticulordens.Fechaorden as Fecha_Orden',
                    'detaarticulordens.Estado_id as Estado_id',
                    'detaarticulordens.mipres',
                    'c.Nombre as Nombre',
                    'c.Codigo as Codigo',
                    'c.Requiere_autorizacion as Requiere_Autorizacion'
                )
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                    ->whereIn('detaarticulordens.Estado_id', [15,37])
                    ->whereMonth('detaarticulordens.Fechaorden', $request->month)
                    ->whereYear('detaarticulordens.Fechaorden',$request->year)

                    ->get();
            }])
            ->whereIn('da.Estado_id', [15,37])
            ->whereMonth('da.Fechaorden', $request->month)
            ->whereYear('da.Fechaorden',$request->year);
            if($request->cedula){
                $query->where('p.Num_Doc',$request->cedula);
            }
            if($request->orden){
                $query->where('ordens.id',$request->orden);
            }
//            ->where('cie10p.Esprimario', true)
           $query->distinct();

        return $query;
    }

    public function oncologicalOrdersToAuthorizeByMonth($request){

        return $ordens = citapaciente::select(['cita_paciente.id','o.paginacion','to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant'),
            'p.Num_Doc', DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'o.scheme_name','cie10.Descripcion as Descripcion_CIE10',])
            ->join('ordens as o', 'o.Cita_id', 'cita_paciente.id')
            ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'o.Tiporden_id')
            ->join('detaarticulordens as da', 'o.id', 'da.Orden_id')
            ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
            ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
            ->with(['ordens' => function($query){
                $query->select([
                    'ordens.id',
                    'ordens.Cita_id',
                    'ordens.paginacion',
                    'ordens.day',

                    'ordens.FechaAgendamiento',
                    'ordens.estadoEnfermeria',
                    'ordens.created_at'
                ])
                    ->join('detaarticulordens as da','da.Orden_id','ordens.id')
                    ->where('da.Estado_id',15)
                    ->where('ordens.Tiporden_id', 7)
                    ->distinct()
                    ->get();
            },'ordens.detaarticulordens' => function($query) use ($request){
                $query->select([
                    'detaarticulordens.id as id',
                    'detaarticulordens.created_at as FechaOrdenamiento',
                    'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                    'detaarticulordens.codesumi_id as Codesumi_id',
                    'detaarticulordens.Via as Via',
                    'detaarticulordens.Unidadmedida as Unidad_Medida',
                    'detaarticulordens.Frecuencia as Frecuencia',
                    'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                    'detaarticulordens.Duracion as Duracion',
                    'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                    'detaarticulordens.Nummeses as Num_Meses',
                    'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                    'detaarticulordens.Orden_id',
                    'detaarticulordens.Fechaorden as Fecha_Orden',
                    'detaarticulordens.Estado_id as Estado_id',
                    'c.Nombre as Nombre',
                    'c.Codigo as Codigo',
                    'c.Requiere_autorizacion as Requiere_Autorizacion',
                    'au.created_at as fechaAutorizacion'
                ])
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                    ->leftjoin('autorizacions as au','au.Articulorden_id','detaarticulordens.id')
                    ->where('detaarticulordens.Estado_id', 15)
                    ->whereMonth('detaarticulordens.created_at', $request->month)
                    ->get();
            }])
            ->where('o.Tiporden_id', 7)
            ->whereIn('da.Estado_id', [15])
            ->whereMonth('da.created_at', $request->month)
            ->groupBy('cita_paciente.id','o.paginacion', 'to.Nombre','p.Num_Doc','p.Segundo_Ape','p.Primer_Ape','p.Primer_Nom','p.SegundoNom','o.scheme_name','cie10.Descripcion');



        Orden::select([
            'ordens.id',
            'ordens.Fechaorden',
            'ordens.created_at',
            'ordens.Tiporden_id',
            'user.name as User_Name',
            'user.apellido as User_LastName',
            'user.Firma as Medico_Firma',
            'cp.id as citaPaciente_id',
            'p.id as Paciente_id',
            'p.Departamento',
            'p.created_at as paciente_created',
            'p.Primer_Nom as Primer_Nom',
            'p.SegundoNom as Segundo_Nom',
            'p.Primer_Ape as Primer_Ape',
            'p.Segundo_Ape as Segundo_Ape',
            'p.Tipo_Doc as Tipo_Doc',
            'p.Num_Doc as Cedula',
            'p.Edad_Cumplida as Edad_Cumplida',
            'p.Sexo as Sexo',
            'p.IPS as IpsAtencion',
            'p.Direccion_Residencia as Direccion_Residencia',
            'p.Correo1 as Correo',
            'p.Telefono as Telefono',
            'p.Celular1 as Celular',
            'p.Tipo_Afiliado as Tipo_Afiliado',
            'p.Estado_Afiliado as Estado_Afiliado',
            'p.Mpio_Afiliado as Municipio',
            's.Nombre as Nombre_IPS',
            's.Direccion as Direccion_IPS',
            's.Telefono1 as Telefono_IPS',
            'cie10.Codigo_CIE10 as Codigo_CIE10',
            'cie10.Descripcion as Descripcion_CIE10',
        ])
            ->join('Users as user', 'ordens.Usuario_id', 'user.id')
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('Pacientes as p', 'cp.Paciente_id', 'p.id')
            ->leftJoin('sedeproveedores as s', 'p.IPS',  DB::raw("CONVERT(VARCHAR, s.id)"))
            ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cp.id')
            ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
            ->whereHas('detaarticulordens', function ($query){
                $query->where('detaarticulordens.Estado_id', 15);
            })
            ->with([
                'detaarticulordens' => function ($query) use ($request) {
                    $query->select(
                        'detaarticulordens.id as id',
                        'detaarticulordens.created_at as FechaOrdenamiento',
                        'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                        'detaarticulordens.codesumi_id as Codesumi_id',
                        'detaarticulordens.Via as Via',
                        'detaarticulordens.Unidadmedida as Unidad_Medida',
                        'detaarticulordens.Frecuencia as Frecuencia',
                        'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                        'detaarticulordens.Duracion as Duracion',
                        'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                        'detaarticulordens.Nummeses as Num_Meses',
                        'detaarticulordens.Observacion as Observacion',
                        'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                        'detaarticulordens.Orden_id',
                        'detaarticulordens.Fechaorden as Fecha_Orden',
                        'detaarticulordens.Estado_id as Estado_id',
                        'c.Nombre as Nombre',
                        'c.Codigo as Codigo',
                        'c.Requiere_autorizacion as Requiere_Autorizacion'
                    )
                        ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                        ->where('detaarticulordens.Estado_id', 15)
                        ->whereMonth('detaarticulordens.created_at', $request->month)
                        ->get();
                }
            ])
            ->where('ordens.Tiporden_id', 7);
            //->where('cie10p.Esprimario', true);
    }

    public function ordersPendingTypeOncology($request){
        $query = Orden::select(['ordens.id','ordens.Cita_id','ordens.Fechaorden','ordens.paginacion','ordens.Estado_id','to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant'),'ordens.day'])
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
            ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
            ->where('p.Num_Doc', $request->Num_Doc)
            ->where('ordens.Tiporden_id',7)
            ->whereIn('da.Estado_id', [35])
            ->groupBy('ordens.id','ordens.Cita_id','ordens.Fechaorden','ordens.paginacion','ordens.Estado_id','to.Nombre','ordens.day')
            ->havingRaw('COUNT(da.id) > 0');
        return $query;
    }

    public function ordersOncologyDispensed(){
        $query = Codesumi::select([
            'codesumis.id',
            'codesumis.Nombre as medicamento',
            'codesumis.Codigo as Codigo',
            DB::raw('SUM(de.Cantidadmensualdisponible) as cantidad'),
            'da.concentracion as concentracion',
            'ba.stock'
        ])
            ->join('detallearticulos as da','da.Codesumi_id','codesumis.id')
            ->join('detaarticulordens as de','de.codesumi_id','codesumis.id')
            ->join('ordens as or','or.id','de.Orden_id')
            ->join('bodegarticulos as ba','da.id','ba.Detallearticulo_id')
            ->with(['detaarticulordens' => function ($query){
                $query->select([
                    'detaarticulordens.id as codigoDetalle',
                    'Codesumi_id',
                    'or.id as codigoOrden',
                    'p.Segundo_Ape',
                    'p.Primer_Ape',
                    'p.Primer_Nom',
                    'SegundoNom',
                    'Num_Doc',
                    'Descripcion',
                    'detaarticulordens.Estado_id',
                    'or.page',
                    'or.total_pages',
                    'or.day'
                ])
                    ->join('ordens as or','or.id','detaarticulordens.Orden_id')
                    ->join('cita_paciente as cp','cp.id','or.Cita_id')
                    ->join('pacientes as p','p.id','cp.Paciente_id')
                    ->join('cie10pacientes as ci','Citapaciente_id','cp.id')
                    ->join('cie10s as c','ci.Cie10_id','c.id')
                    ->where('or.Tiporden_id',7)
                    ->whereIn('detaarticulordens.Estado_id',[7,1]);
            }
            ])
            ->where('or.Tiporden_id',7)
            ->whereIn('de.Estado_id',[7,1])
            ->groupBy('codesumis.Nombre','codesumis.Codigo','codesumis.id','de.Cantidadmensualdisponible','da.concentracion','ba.stock');
        //$query = Orden::where('Tiporden_id',7);
        return $query;
    }

    public function updateOrdens($request,$orden)
    {
        $orden->update($request->all());
    }

    public function getOrdersOncologyForNursing(){
        return $query = Orden::select([
            'ordens.id',
            'p.Num_doc',
            DB::raw("CONCAT(Segundo_Ape,' ',Primer_Ape,' ',Primer_Nom,' ',SegundoNom) as paciente"),
            'ordens.scheme_name as esquema',
            'ordens.page as ciclo',
            'ordens.total_pages as total',
            'ordens.day',
            'ordens.FechaAgendamiento',
            'e.Nombre as estado',
            'ordens.Estado_id',
            'ordens.estadoEnfermeria'
        ])
            ->join('cita_paciente as cp','cp.id','ordens.Cita_id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->join('estados as e','e.id','ordens.Estado_id')
            ->with([
                'detaarticulordens' => function ($query){
                    $query->select(
                        'detaarticulordens.id as id',
                        'detaarticulordens.created_at as FechaOrdenamiento',
                        'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                        'detaarticulordens.codesumi_id as Codesumi_id',
                        'detaarticulordens.Via as Via',
                        'detaarticulordens.Unidadmedida as Unidad_Medida',
                        'detaarticulordens.Frecuencia as Frecuencia',
                        'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                        'detaarticulordens.Duracion as Duracion',
                        'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                        'detaarticulordens.Nummeses as Num_Meses',
                        'detaarticulordens.Observacion as Observacion',
                        'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                        'detaarticulordens.Orden_id',
                        'detaarticulordens.Fechaorden as Fecha_Orden',
                        'detaarticulordens.Estado_id as Estado_id',
                        'detaarticulordens.notaFarmacia',
                        'c.Nombre as Nombre',
                        'c.Codigo as Codigo',
                        'c.Requiere_autorizacion as Requiere_Autorizacion',
                        'au.created_at as fechaAutorizacion'
                    )
                        ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                        ->leftjoin('autorizacions as au','au.Articulorden_id','detaarticulordens.id')
                        ->get();
                }
            ])
            ->where('ordens.Tiporden_id',7);
    }

    public function getOrdersPending($request){
        $query = Orden::select(['ordens.id','ordens.Cita_id','ordens.Fechaorden','ordens.paginacion','ordens.Estado_id','to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant'),'ordens.day'])
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
            ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
            ->where('p.Num_Doc', $request->Num_Doc)
            ->whereIn('da.Estado_id', [18,19])
            ->groupBy('ordens.id','ordens.Cita_id','ordens.Fechaorden','ordens.paginacion','ordens.Estado_id','to.Nombre','ordens.day')
            ->havingRaw('COUNT(da.id) > 0');
        return $query;
    }

}
