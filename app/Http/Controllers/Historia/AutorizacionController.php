<?php

namespace App\Http\Controllers\Historia;

use App\User;
use Carbon\Carbon;
use App\Modelos\Orden;
use App\Modelos\Codesumi;
use App\Modelos\Cuporden;
use App\Modelos\Paciente;
use App\Modelos\Auditoria;
use App\Modelos\Tarifario;
use Illuminate\Http\Request;
use App\Modelos\Autorizacion;
use App\Modelos\Sedeproveedore;
use App\Modelos\Detaarticulorden;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;

class AutorizacionController extends Controller
{
    public function listaAutorizacionesMedicamentos(Request $request)
    {
        $ordereds = Orden::getRepository()->allOrdersByMonth($request)->paginate($request->items);
        return response()->json($ordereds, 200);
    }

    public function getOncologicalOrdersToAuthorizate(Request $request)
    {
        $ordereds = Orden::getRepository()->oncologicalOrdersToAuthorizeByMonth($request)->get();

        return response()->json($ordereds, 200);
    }

    public function listAuthMedicByState(Request $request)
    {
        if(auth()->user()->hasPermissionTo('order.audit-all-orders'))
        {
            $ordereds = Orden::select([
                'ordens.*',
                'user.name as User_Name',
                'user.apellido as User_LastName',
                'user.Firma as Medico_Firma',
                'cita_paciente.id as citaPaciente_id',
                'p.id as Paciente_id',
                'p.Departamento',
                'p.Ut',
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
                'cie10.Descripcion as Descripcion_CIE10'])
                ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
                ->join('Users as user', 'ordens.Usuario_id', 'user.id')
                ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
                ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
                ->leftJoin('Municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
                ->leftJoin('sedeproveedores as s', 'p.IPS', DB::raw("CONVERT(VARCHAR, s.id)"))
                ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
                ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
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
                        'c.Nombre as Nombre',
                        'c.Codigo as Codigo',
                        'c.Requiere_autorizacion as Requiere_Autorizacion',
                        'a.Nota as Auth_Nota',
                        'a.updated_at as FechaAutorizacion',
                        'u.name as Auth_Name',
                        'u.apellido as Auth_Apellido',
                        'u.Firma as Auth_Firma'
                    )
                        ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                        ->leftJoin('autorizacions as a', 'detaarticulordens.id', 'a.Articulorden_id')
                        ->leftJoin('Users as u', 'a.Usuario_id', 'u.id')
                        ->where('detaarticulordens.Estado_id', $request->status)
                        ->get();
                }])
                ->where('da.Estado_id', $request->status)
                ->Where('p.Num_Doc', $request->cedula)
                ->Where('ordens.Usuario_id', auth()->user()->id)
                //->where('cie10p.Esprimario', true)
                ->distinct()
                ->get();
        }else{
            $ordereds = Orden::select([
                'ordens.*',
                'user.name as User_Name',
                'user.apellido as User_LastName',
                'user.Firma as Medico_Firma',
                'cita_paciente.id as citaPaciente_id',
                'p.id as Paciente_id',
                'p.Departamento',
                'p.Ut',
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
                'cie10.Descripcion as Descripcion_CIE10'])
                ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
                ->join('Users as user', 'ordens.Usuario_id', 'user.id')
                ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
                ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
                ->leftJoin('Municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
                ->leftJoin('sedeproveedores as s', 'p.IPS', DB::raw("CONVERT(VARCHAR, s.id)"))
                ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
                ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
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
                        'c.Nombre as Nombre',
                        'c.Codigo as Codigo',
                        'c.Requiere_autorizacion as Requiere_Autorizacion',
                        'a.Nota as Auth_Nota',
                        'a.updated_at as FechaAutorizacion',
                        'u.name as Auth_Name',
                        'u.apellido as Auth_Apellido',
                        'u.Firma as Auth_Firma'
                    )
                        ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                        ->leftJoin('autorizacions as a', 'detaarticulordens.id', 'a.Articulorden_id')
                        ->leftJoin('Users as u', 'a.Usuario_id', 'u.id')
                        ->where('detaarticulordens.Estado_id', $request->status)
                        ->get();
                }])
                ->where('da.Estado_id', $request->status)
                ->Where('p.Num_Doc', $request->cedula)
                //->where('cie10p.Esprimario', true)
                ->distinct()
                ->get();
        }


        return response()->json($ordereds, 200);
    }

    public function listaAutorizacionesServicios(Request $request)
    {
        $ordereds = Orden::select([
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
            'sede_paciente.Nombre as Nombre_IPS',
            'cie10.Codigo_CIE10 as Codigo_CIE10',
            'cie10.Descripcion as Descripcion_CIE10',
            'sp.Nombre as nombrePrestador',
            'sp.Direccion as direccionPrestador',
            'pr.Nit as nitPrestador',
            'sp.Telefono1 as telefono1Prestador',
            'sp.Telefono2 as telefono2Prestador',
            'sp.Codigo_habilitacion as codigoHabilitacion',
            'mp.Nombre as municipioPrestador',
            'dp.Nombre as departamentoPrestador'])
            ->join('Cupordens as co', 'ordens.id', 'co.Orden_id')
            ->join('Users as user', 'ordens.Usuario_id', 'user.id')
            ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
            ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->leftJoin('Municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR,m.id)"))
            ->leftJoin('sedeproveedores as sede_paciente', 'p.IPS', DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
            ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
            ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
            ->leftJoin('sedeproveedores as sp', 'co.Ubicacion_id', DB::raw("CONVERT(VARCHAR, sp.id)"))
            ->leftJoin('prestadores as pr', 'sp.Prestador_id', DB::raw("CONVERT(VARCHAR,pr.id)"))
            ->leftJoin('Municipios as mp', 'sp.Municipio_id', 'mp.id')
            ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
            ->with(['cupordens' => function ($query) use ($request) {
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
                    ->where('Cupordens.Estado_id', 15)
                    ->whereMonth('Cupordens.created_at', '=', $request->month)
                    ->get();
            }])
            ->where('co.Estado_id', 15)
            ->whereNotNull('co.Cup_id')
            ->whereMonth('co.created_at', '=', $request->month)
            // ->where('cie10p.Esprimario', true)
            ->distinct()
            ->get();

        return response()->json($ordereds, 200);
    }

    public function listAuthServByState(Request $request)
    {
        $prestador = auth()->user()->prestador_id;
        if(auth()->user()->hasPermissionTo('order.audit-all-orders') && !$prestador)
        {
            $ordereds = Orden::select([
                'ordens.*',
                'to.Nombre as tipoOrden',
                'user.name as User_Name',
                'user.apellido as User_LastName',
                'user.Firma as Medico_Firma',
                'cita_paciente.id as citaPaciente_id',
                'p.id as Paciente_id',
                'p.Departamento',
                'p.Ut',
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
                'sa.Nombre as Prestadores',
                'm.Nombre as Municipio',
                'sede_paciente.Nombre as Nombre_IPS',
                'cie10.Codigo_CIE10 as Codigo_CIE10',
                'cie10.Descripcion as Descripcion_CIE10',
                'sp.Nombre as nombrePrestador',
                'sp.Direccion as direccionPrestador',
                'pr.Nit as nitPrestador',
                'sp.Telefono1 as telefono1Prestador',
                'sp.Telefono2 as telefono2Prestador',
                'sp.Codigo_habilitacion as codigoHabilitacion',
                'mp.Nombre as municipioPrestador',
                'dp.Nombre as departamentoPrestador'])
                ->join('Cupordens as co', 'ordens.id', 'co.Orden_id')
                ->join('Tipordens as to', 'to.id', 'ordens.Tiporden_id')
                ->join('Users as user', 'ordens.Usuario_id', 'user.id')
                ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
                ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
                ->join('sedeproveedores as sa', 'sa.id', 'p.IPS')
                ->join('prestadores as pres', 'pres.id', 'sa.Prestador_id')
                ->join('Municipios as m', 'p.Mpio_Atencion', 'm.id')
                ->leftJoin('sedeproveedores as sede_paciente', 'p.IPS', DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
                ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
                ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
                ->leftJoin('sedeproveedores as sp', 'co.Ubicacion_id', DB::raw("CONVERT(VARCHAR, sp.id)"))
                ->leftJoin('prestadores as pr', 'sp.Prestador_id', 'pr.id')
                ->leftJoin('Municipios as mp', 'sp.Municipio_id', DB::raw("CONVERT(VARCHAR, mp.id)"))
                ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
                ->with(['cupordens' => function ($query) use ($request) {
                    $query->select(
                        'Cupordens.id as id',
                        'Cupordens.created_at as FechaOrdenamiento',
                        'Cupordens.Cup_id as Cup',
                        'Cupordens.Cantidad as Cup_Cantidad',
                        'Cupordens.Orden_id as Orden_id',
                        'Cupordens.Observacionorden as observaciones',
                        'Cupordens.Estado_id as Estado_id',
                        'cupordens.fecha_solicitada',
                        'cupordens.fecha_sugerida',
                        'cupordens.fecha_cancelacion',
                        'cupordens.cancelacion',
                        'cupordens.observaciones',
                        'cupordens.responsable',
                        'cupordens.motivo',
                        'cupordens.causa',
                        'cupordens.soportes',
                        'cupordens.cirujano',
                        'cupordens.especialidad',
                        'cupordens.fecha_Preanestesia',
                        'cupordens.fecha_cirugia',
                        'cupordens.fecha_ejecucion',
                        'c.id as Cup_Id',
                        'c.Nombre as Cup_Nombre',
                        'c.Codigo as Cup_Codigo',
                        'cp.Descripcion as Servicio_Nombre',
                        'cp.Codigo as Servicio_Codigo',
                        's.id as Sede_Id',
                        's.Nombre as Sede_Nombre',
                        's.Direccion as Sede_Direccion',
                        's.Telefono1 as Sede_Telefono',
                        's.Codigo_habilitacion as codigoHabilitacion',
                        'pr.Nit as nitPrestador',
                        'mp.Nombre as municipioPrestador',
                        'dp.Nombre as departamentoPrestador',
                        'a.Nota as Auth_Nota',
                        'a.updated_at as FechaAutorizacion',
                        'u.name as Auth_Name',
                        'u.apellido as Auth_Apellido',
                        'u.Firma as Auth_Firma'
                    )
                        ->leftJoin('Cups as c', 'Cupordens.Cup_id', 'c.id')
                        ->leftJoin('codepropios as cp', 'Cupordens.Serviciopropio_id', 'cp.id')
                        ->leftJoin('autorizacions as a', 'Cupordens.id', 'a.Cuporden_id')
                        ->leftJoin('Users as u', 'a.Usuario_id', 'u.id')
                        ->leftJoin('sedeproveedores as s', 'Cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
                        ->leftJoin('prestadores as pr', 's.Prestador_id', 'pr.id')
                        ->leftJoin('Municipios as mp', 's.Municipio_id', DB::raw("CONVERT(VARCHAR, mp.id)"))
                        ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
                        ->where('Cupordens.Estado_id', $request->status)
                        ->get();
                }])
                ->where('co.Estado_id', $request->status)
                ->where('p.Num_Doc', $request->cedula)
                ->whereIn('ordens.Tiporden_id', [2, 4, 6])
                // ->where('cie10p.Esprimario', true)
                ->Where('ordens.Usuario_id', auth()->user()->id)
                ->distinct()
                ->get();
        }elseif(auth()->user()->hasPermissionTo('confirmar.datos.servicio')  && $prestador){
            $query = Cuporden::select(
                'o.*',
                'p.id as Paciente_id',
                'p.Departamento',
                'p.Ut',
                'p.created_at as paciente_created',
                DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
                'p.Primer_Nom as Primer_Nom',
                'p.SegundoNom as Segundo_Nom',
                'p.Primer_Ape as Primer_Ape',
                'p.Segundo_Ape as Segundo_Ape',
                'p.Tipo_Doc as Tipo_Doc',
                'p.Num_Doc as Cedula',
                'p.Edad_Cumplida as Edad_Cumplida',
                'p.Sexo as Sexo',
                'p.IPS as IpsAtencion',
                'sa.Nombre as Prestadores',
                'p.Direccion_Residencia as Direccion_Residencia',
                'p.Correo1 as Correo',
                'p.Telefono as Telefono',
                'p.Celular1 as Celular',
                'p.Tipo_Afiliado as Tipo_Afiliado',
                'p.Estado_Afiliado as Estado_Afiliado',
                'p.Mpio_Afiliado',
                'p.Departamento',
                'cupordens.id as idServicio',
                'cupordens.created_at as FechaOrdenamiento',
                'cupordens.fecha_orden as fechaVigencia',
                DB::raw("DATEDIFF(DAY,cupordens.fecha_orden,CONVERT(varchar,GETDATE(),23)) as diasVencido"),
                'cupordens.Cup_id as Cup',
                'cupordens.Cantidad as Cup_Cantidad',
                'cupordens.Orden_id as Orden_id',
                'cupordens.Observacionorden as observaciones',
                'cupordens.Estado_id as Estado_id',
                'cupordens.fecha_solicitada',
                'cupordens.fecha_sugerida',
                'cupordens.fecha_cancelacion',
                'cupordens.cancelacion',
                'cupordens.observaciones AS observacionesPrestador',
                'cupordens.responsable',
                'cupordens.motivo',
                'cupordens.causa',
                'cupordens.soportes',
                'cupordens.cirujano',
                'cupordens.especialidad',
                'cupordens.fecha_Preanestesia',
                'cupordens.fecha_cirugia',
                'cupordens.fecha_ejecucion',
                'cupordens.fecha_resultado',
                'cupordens.Ubicacion_id',
                'c.id as Cup_Id',
                'c.Nombre as Cup_Nombre',
                'c.Codigo as Cup_Codigo',
                'cp.Descripcion as Servicio_Nombre',
                'cp.Codigo as Servicio_Codigo',
                's.id as Sede_Id',
                's.Nombre as Sede_Nombre',
                's.Direccion as Sede_Direccion',
                's.Telefono1 as Sede_Telefono',
                's.Codigo_habilitacion as codigoHabilitacion',
                'pr.Nit as nitPrestador',
                'mp.Nombre as municipioPrestador',
                'dp.Nombre as departamentoPrestador',
                'a.Nota as Auth_Nota',
                'a.updated_at as FechaAutorizacion',
                'u.name as Auth_Name',
                'u.apellido as Auth_Apellido',
                'u.Firma as Auth_Firma',
                'e.nombre as Estado',
                'cie10.Codigo_CIE10 as Codigo_CIE10',
                'cie10.Descripcion as Descripcion_CIE10')
                ->join('ordens as o','cupordens.Orden_id','o.id')
                ->join('cita_paciente as cita_paciente', 'o.Cita_id', 'cita_paciente.id')
                ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
                ->join('sedeproveedores as sa', 'sa.id', 'p.IPS')
                ->join('estados as e','cupordens.Estado_id','e.id')
                ->leftJoin('Cups as c', 'cupordens.Cup_id', 'c.id')
                ->leftJoin('codepropios as cp', 'cupordens.Serviciopropio_id', 'cp.id')
                ->leftJoin('autorizacions as a', 'cupordens.id', 'a.Cuporden_id')
                ->leftJoin('cie10pacientes as cie10p',function ($join){
                    $join->on('cie10p.Citapaciente_id', '=', 'cita_paciente.id');
                    $join->on('cie10p.Esprimario', '=', DB::raw("1"));
                })
                ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
                ->leftJoin('Users as u', 'a.Usuario_id', 'u.id')
                ->leftJoin('sedeproveedores as s', 'cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
                ->leftJoin('prestadores as pr', 's.Prestador_id', 'pr.id')
                ->leftJoin('Municipios as mp', 's.Municipio_id', DB::raw("CONVERT(VARCHAR, mp.id)"))
                ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
                ->where('cupordens.created_at','>','2020-10-01 00:00:00.000')
                ->where('cancelacion',$request->estadoPrestadores);
            if($request->numeroDocumento){
                $query->where('p.Num_Doc',$request->numeroDocumento);
            }else{
               $query->whereMonth('cupordens.fecha_orden', $request->month)
                   ->whereYear('cupordens.fecha_orden',$request->year);
                $query->where('cupordens.fecha_orden','>=','2020-01-01 00:00:00.000');
            }
            $query->whereIn('o.Tiporden_id', [2, 4, 6])
                ->whereIn('cupordens.Estado_id',[1,7])
                ->whereIn('Cupordens.Ubicacion_id',[$request->sede])
               ->whereIn('Cupordens.Ubicacion_id',function ($q) use ($prestador){
                   $q->select('s.id')
                       ->from('prestadores')
                       ->join('sedeproveedores as s','prestadores.id','s.Prestador_id')
                       ->where('prestadores.id',$prestador)
                       ->distinct();
               })
                ->orderBy('cupordens.created_at','ASC');

            $ordereds = $query->get();
        } else{
            $ordereds = Orden::select([
                'ordens.*',
                'to.Nombre as tipoOrden',
                'user.name as User_Name',
                'user.apellido as User_LastName',
                'user.Firma as Medico_Firma',
                'cita_paciente.id as citaPaciente_id',
                'p.id as Paciente_id',
                'p.Departamento',
                'p.Ut',
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
                'pres.Nombre as Prestadores',
                'p.Direccion_Residencia as Direccion_Residencia',
                'p.Correo1 as Correo',
                'p.Telefono as Telefono',
                'p.Celular1 as Celular',
                'p.Tipo_Afiliado as Tipo_Afiliado',
                'p.Estado_Afiliado as Estado_Afiliado',
                'm.Nombre as Municipio',
                'sede_paciente.Nombre as Nombre_IPS',
                'cie10.Codigo_CIE10 as Codigo_CIE10',
                'cie10.Descripcion as Descripcion_CIE10',
                'sp.Nombre as nombrePrestador',
                'sp.Direccion as direccionPrestador',
                'pr.Nit as nitPrestador',
                'pres.Nombre as Prestadores',
                'sp.Telefono1 as telefono1Prestador',
                'sp.Telefono2 as telefono2Prestador',
                'sp.Codigo_habilitacion as codigoHabilitacion',
                'mp.Nombre as municipioPrestador',
                'dp.Nombre as departamentoPrestador'])
                ->join('Cupordens as co', 'ordens.id', 'co.Orden_id')
                ->join('Tipordens as to', 'to.id', 'ordens.Tiporden_id')
                ->join('Users as user', 'ordens.Usuario_id', 'user.id')
                ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
                ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
                ->join('sedeproveedores as sa', 'sa.id', 'p.IPS')
                ->join('prestadores as pres', 'pres.id', 'sa.Prestador_id')
                ->leftJoin('Municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
                ->leftJoin('sedeproveedores as sede_paciente', 'p.IPS', DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
                ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
                ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
                ->leftJoin('sedeproveedores as sp', 'co.Ubicacion_id', DB::raw("CONVERT(VARCHAR, sp.id)"))
                ->leftJoin('prestadores as pr', 'sp.Prestador_id', 'pr.id')
                ->leftJoin('Municipios as mp', 'sp.Municipio_id', DB::raw("CONVERT(VARCHAR, mp.id)"))
                ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
                ->with(['cupordens' => function ($query) use ($request) {
                    $query->select(
                        'cupordens.id as id',
                        'cupordens.created_at as FechaOrdenamiento',
                        'cupordens.Cup_id as Cup',
                        'cupordens.Cantidad as Cup_Cantidad',
                        'cupordens.Orden_id as Orden_id',
                        'cupordens.Observacionorden as observaciones',
                        'cupordens.Estado_id as Estado_id',
                        'cupordens.fecha_solicitada',
                        'cupordens.fecha_sugerida',
                        'cupordens.fecha_cancelacion',
                        'cupordens.cancelacion',
                        'cupordens.observaciones AS observacionesPrestador',
                        'cupordens.responsable',
                        'cupordens.motivo',
                        'cupordens.causa',
                        'cupordens.soportes',
                        'cupordens.cirujano',
                        'cupordens.especialidad',
                        'cupordens.fecha_Preanestesia',
                        'cupordens.fecha_cirugia',
                        'cupordens.fecha_ejecucion',
                        'c.id as Cup_Id',
                        'c.Nombre as Cup_Nombre',
                        'c.Codigo as Cup_Codigo',
                        'cp.Descripcion as Servicio_Nombre',
                        'cp.Codigo as Servicio_Codigo',
                        's.id as Sede_Id',
                        's.Nombre as Sede_Nombre',
                        's.Direccion as Sede_Direccion',
                        's.Telefono1 as Sede_Telefono',
                        's.Codigo_habilitacion as codigoHabilitacion',
                        'pr.Nit as nitPrestador',
                        'mp.Nombre as municipioPrestador',
                        'dp.Nombre as departamentoPrestador',
                        'a.Nota as Auth_Nota',
                        'a.updated_at as FechaAutorizacion',
                        'u.name as Auth_Name',
                        'u.apellido as Auth_Apellido',
                        'u.Firma as Auth_Firma'
                    )
                        ->leftJoin('Cups as c', 'Cupordens.Cup_id', 'c.id')
                        ->leftJoin('codepropios as cp', 'Cupordens.Serviciopropio_id', 'cp.id')
                        ->leftJoin('autorizacions as a', 'Cupordens.id', 'a.Cuporden_id')
                        ->leftJoin('Users as u', 'a.Usuario_id', 'u.id')
                        ->leftJoin('sedeproveedores as s', 'Cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
                        ->leftJoin('prestadores as pr', 's.Prestador_id', 'pr.id')
                        ->leftJoin('Municipios as mp', 's.Municipio_id', DB::raw("CONVERT(VARCHAR, mp.id)"))
                        ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
                        ->where('Cupordens.Estado_id', $request->status)
                        ->get();
                }])
                ->where('co.Estado_id', $request->status)
                ->where('p.Num_Doc', $request->cedula)
                ->whereIn('ordens.Tiporden_id', [2, 4, 6])
                // ->where('cie10p.Esprimario', true)
                ->distinct()
                ->get();
        }

        return response()->json($ordereds, 200);
    }

    public function OwnServicesOrdened(Request $request)
    {

        $ordereds = Orden::select([
            'ordens.id',
            'ordens.created_at',
            'ordens.Cita_id',
            'ordens.Tiporden_id',
            'u.name as User_Name',
            'u.apellido as User_LastName',
            'u.Firma as Medico_Firma',
            'cp.id as citaPaciente_id',
            'p.id as Paciente_id',
            'p.Departamento',
            'p.created_at as paciente_created',
            'p.Primer_Nom as Primer_Nom',
            'p.SegundoNom as Segundo_Nom',
            'p.Primer_Ape as Primer_Ape',
            'p.Segundo_Ape as Segundo_Ape',
            'p.id as Paciente_id',
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
            'sede_paciente.Nombre as Nombre_IPS'])
            ->join('users as u', 'ordens.Usuario_id', 'u.id')
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->leftJoin('municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
            ->leftJoin('sedeproveedores as sede_paciente', 'p.IPS',  DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
            ->with([
                'cupordens'           => function ($query) use ($request) {
                    $query->select(
                        'Cupordens.id',
                        'Cupordens.created_at as FechaOrdenamiento',
                        'Cupordens.Cantidad as Cup_Cantidad',
                        'Cupordens.Orden_id as Orden_id',
                        'Cupordens.Observacionorden as observaciones',
                        'Cupordens.Estado_id as Estado_id',
                        'c.id as Servicio_Id',
                        'c.Descripcion as Servicio_Nombre',
                        'c.Codigo as Cup_Codigo',
                        's.id as Sede_Id',
                        's.Nombre as Sede_Nombre',
                        's.Direccion as Sede_Direccion',
                        's.Telefono1 as Sede_Telefono'
                    )
                        ->join('codepropios as c', 'Cupordens.Serviciopropio_id', 'c.id')
                        ->leftJoin('sedeproveedores as s', 'Cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
                        ->where('Cupordens.Estado_id', 15)
                        ->whereMonth('Cupordens.created_at', $request->month)
                        ->get();
                },
                'citapaciente'        => function ($query) {
                    $query->select(['id']);
                },
                'citapaciente.cie10s' => function ($query) {
                    $query->select(['Descripcion', 'Codigo_CIE10']);
                },
            ])
            ->whereHas('cupordens', function ($query) use ($request) {$query->where('Estado_id', 15)->whereMonth('Cupordens.created_at', $request->month);})
            ->where('ordens.Tiporden_id', 6)
            ->get();

        return response()->json($ordereds, 200);
    }

    public function listaAutorizacionesServiciosByType(Request $request)
    {
        $ordereds = Orden::select([
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
            'p.id as Paciente_id',
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
            'sede_paciente.Nombre as Nombre_IPS',
            'cie10.Codigo_CIE10 as Codigo_CIE10',
            'cie10.Descripcion as Descripcion_CIE10'])
            ->join('Cupordens as co', 'ordens.id', 'co.Orden_id')
            ->join('Users as user', 'ordens.Usuario_id', 'user.id')
            ->join('cita_paciente as cita_paciente', 'ordens.Cita_id', 'cita_paciente.id')
            ->join('Pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('Municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
            ->join('Cups as c', 'co.Cup_id', 'c.id')
            ->join('cupfamilias as cf', 'c.id', 'cf.Cup_id')
            ->join('familias as f', 'cf.Familia_id', 'f.id')
            ->leftJoin('sedeproveedores as sede_paciente', 'p.IPS', DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
            ->leftJoin('cie10pacientes as cie10p', 'cie10p.Citapaciente_id', 'cita_paciente.id')
            ->leftJoin('cie10s as cie10', 'cie10.id', 'cie10p.Cie10_id')
            ->with(['cupordens' => function ($query) use ($request) {
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
                    's.Telefono1 as Sede_Telefono',
                    'f.id as TipoFamilia_Id',
                    'f.Nombre as TipoFamilia_Nombre'
                )
                    ->join('Cups as c', 'Cupordens.Cup_id', 'c.id')
                    ->join('cupfamilias as cf', 'c.id', 'cf.Cup_id')
                    ->join('familias as f', 'cf.Familia_id', 'f.id')
                    ->leftJoin('sedeproveedores as s', 'Cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
                    ->where('Cupordens.Estado_id', 15)
                    ->whereMonth('Cupordens.created_at', '=', $request->month)
                    ->where('f.id', $request->family)
                    ->get();
            }])
            ->where('co.Estado_id', 15)
            ->whereMonth('co.created_at', '=', $request->month)
            ->where('f.id', $request->family)
            // ->where('cie10p.Esprimario', true)
            ->distinct()
            ->get();

        return response()->json($ordereds, 200);
    }

    public function getState($type)
    {
        if ($type == 'Aprobado') {
            return 7;
        } elseif ($type == 'Pendiente') {
            return 15;
        } elseif ($type == 'Inadecuado') {
            return 24;
        } elseif ($type == 'Negado') {
            return 25;
        } elseif ($type == 'Anulado') {
            return 26;
        }
    }

    // public function updateAutMedicamentos($request, $type)
    // {

    //     $state = $this->getState($type);

    //     $detaarticulorden = Detaarticulorden::where('id', $request->idAutorizacion)
    //         ->update([
    //             'Estado_id' => $state
    //         ]);

    //     Autorizacion::create([
    //         "Articulorden_id" => $detaarticulorden->id,
    //         "Usuario_id" => auth()->id(),
    //         "Nota" => $request->observaciones
    //     ]);
    // }

    // public function updateAutServicios($request, $type)
    // {
    //     $state = $this->getState($type);

    //     $cuporden = Cuporden::where('id', $request->idAutorizacion)
    //             ->update([
    //                 'Estado_id' => $state
    //             ]);

    //     Autorizacion::create([
    //         "Cuporden_id" => $cuporden->id,
    //         "Usuario_id" => auth()->id(),
    //         "Nota" => $request->observaciones
    //     ]);
    // }

    public function autorizacionStateAprob(Request $request)
    {
        $type = 'Aprobado';

        if ($request->type == 'Medicamentos') {
            $state = $this->getState($type);
            $ordenReferencia = Orden::find($request->id);
            $ordenes = Orden::join('detaarticulordens as da','da.Orden_id','ordens.id')->select('ordens.*')->where('ordens.Cita_id',$ordenReferencia->Cita_id)->where('da.Estado_id',15)->where('autorizacion',$ordenReferencia->autorizacion)->distinct()->orderBy('ordens.id','ASC')->get();
            foreach ($ordenes as $orden) {
                $orden->update([
                    'Estado_id' => 1
                ]);

                if($request->Tiporden_id == 7){
                    Orden::where('id',$request->id)->update([
                        'Fechaorden' => $request->Fechaorden,

                    ]);
                }
                foreach ($request->autorizaciones as $auth) {
                    $detaarticulorden = Detaarticulorden::where('Orden_id',$orden->id)->where('codesumi_id',$auth['Codesumi_id'])->first();
                    if($detaarticulorden['Fechaorden'] > '2024-04-30'){
                        $detaarticulorden->update([
                            'Estado_id' => 58,
                        ]);
                    }else{
                        $detaarticulorden->update([
                            'Estado_id' => $state,
                        ]);
                    }

                    $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                        ->first();

                    if (!isset($autorizacion)) {
                        Autorizacion::create([
                            "Articulorden_id" => $detaarticulorden->id,
                            "Usuario_id"      => auth()->id(),
                            "Nota"            => $request->observaciones,
                        ]);
                    } else {
                        $autorizacion->update([
                            "Usuario_id" => auth()->id(),
                            "Nota"       => $request->observaciones,
                        ]);
                    }

                    $msg = 'Actualizó el articulo ' . $auth['Nombre'] . ' a estado Aprobado';

                    Auditoria::create([
                        'Descripcion'        => $msg,
                        'Tabla'              => 'Detaarticulorden',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id'           => $detaarticulorden->id,
                        'Motivo'             => $request->observaciones,
                    ]);
                }
            }
            $this->calcularFechaOrdenPaginacion($ordenes[0]);
        } elseif ($request->type == 'Servicios') {
            $state = $this->getState($type);
            $idOrden = 0;
            foreach ($request->autorizaciones as $auth) {
                $idOrden = $auth["Orden_id"];
                $orden = Orden::find($idOrden);


                $cuporden = Cuporden::where('id', $auth['id'])->first();

                $fechaOrdenServicio = date('Y-m-d');
                if($request->posFechar){
                    $fechaOrdenServicio = $request->datePosFechar;
                }
                if($fechaOrdenServicio > '2024-04-30'){
                    $cuporden->update([
                        'Estado_id' => 58,
                        'fecha_orden' => $fechaOrdenServicio
                    ]);
                }else{
                    $cuporden->update([
                        'Estado_id' => $state,
                        'fecha_orden' => $fechaOrdenServicio
                    ]);
                }

                $autorizacion = Autorizacion::where('Cuporden_id', $cuporden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Cuporden_id" => $cuporden->id,
                        "Usuario_id"  => auth()->id(),
                        "Nota"        => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el Cup orden ' . $cuporden->id . ' a estado Aprobado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Cuporden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $cuporden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }
            $orden = Orden::find($idOrden);
            // if($request->posFechar){
            //     $orden->Fechaorden = $request->datePosFechar;
            //     $orden->save();
            // }else{
            $this->calcularFechaOrdenPaginacion($orden);
        } elseif($request->type == 'oncologia'){
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $codigo){
                $orden = Orden::find($codigo["id"]);
                $orden->update([
                    'Estado_id' => 1,
                    'Fechaorden' => $request->fechaAutorizacion
                ]);
                $detaarticulordens = Detaarticulorden::where('Orden_id', $codigo["id"])->get();
                foreach ($detaarticulordens as $detaarticulorden){
                    $codesumi = Codesumi::find($detaarticulorden->codesumi_id);

                    $detaarticulorden->update([
                        'Estado_id' => $state,
                    ]);

                    $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                        ->first();

                    if (!isset($autorizacion)) {
                        Autorizacion::create([
                            "Articulorden_id" => $detaarticulorden->id,
                            "Usuario_id"      => auth()->id(),
                            "Nota"            => $request->observaciones,
                        ]);
                    } else {
                        $autorizacion->update([
                            "Usuario_id" => auth()->id(),
                            "Nota"       => $request->observaciones,
                        ]);
                    }


                    $msg = 'Actualizó el articulo ' . $codesumi->Nombre . ' a estado Aprobado';

                    Auditoria::create([
                        'Descripcion'        => $msg,
                        'Tabla'              => 'Detaarticulorden',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id'           => $detaarticulorden->id,
                        'Motivo'             => $request->observaciones,
                    ]);


                }
            }

        }

        return response()->json('Autorización Aprobada de manera exitosa', 200);
    }

    public function autorizacionStateInad(Request $request)
    {
        $type = 'Inadecuado';

        if ($request->type == 'Medicamentos') {
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $auth) {
                $detaarticulorden = Detaarticulorden::where('id', $auth['id'])->first();

                $detaarticulorden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Articulorden_id" => $detaarticulorden->id,
                        "Usuario_id"      => auth()->id(),
                        "Nota"            => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el articulo ' . $auth['Nombre'] . ' a estado Inadecuado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Detaarticulorden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $detaarticulorden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }

            // $this->updateAutMedicamentos($request, $type);
        } elseif ($request->type == 'Servicios') {
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $auth) {
                $cuporden = Cuporden::where('id', $auth['id'])->first();

                $cuporden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Cuporden_id', $cuporden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Cuporden_id" => $cuporden->id,
                        "Usuario_id"  => auth()->id(),
                        "Nota"        => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el Cup orden ' . $cuporden->id . ' a estado Inadecuado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Cuporden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $cuporden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }
            // $this->updateAutServicios($request, $type);
        } elseif($request->type == 'oncologia'){
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $codigo){

                $detaarticulordens = Detaarticulorden::where('Orden_id', $codigo["id"])->get();
                foreach ($detaarticulordens as $detaarticulorden){
                    $codesumi = Codesumi::find($detaarticulorden->codesumi_id);

                    $detaarticulorden->update([
                        'Estado_id' => $state,
                    ]);

                    $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                        ->first();

                    if (!isset($autorizacion)) {
                        Autorizacion::create([
                            "Articulorden_id" => $detaarticulorden->id,
                            "Usuario_id"      => auth()->id(),
                            "Nota"            => $request->observaciones,
                        ]);
                    } else {
                        $autorizacion->update([
                            "Usuario_id" => auth()->id(),
                            "Nota"       => $request->observaciones,
                        ]);
                    }


                    $msg = 'Actualizó el articulo ' . $codesumi->Nombre . ' a estado Aprobado';

                    Auditoria::create([
                        'Descripcion'        => $msg,
                        'Tabla'              => 'Detaarticulorden',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id'           => $detaarticulorden->id,
                        'Motivo'             => $request->observaciones,
                    ]);


                }
            }

        }

        return response()->json('Autorización en estado Inadecuada de manera exitosa', 200);
    }

    public function autorizacionStateNeg(Request $request)
    {
        $type = 'Negado';

        if ($request->type == 'Medicamentos') {
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $auth) {
                $detaarticulorden = Detaarticulorden::where('id', $auth['id'])->first();

                $detaarticulorden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Articulorden_id" => $detaarticulorden->id,
                        "Usuario_id"      => auth()->id(),
                        "Nota"            => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el articulo ' . $auth['Nombre'] . ' a estado Negado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Detaarticulorden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $detaarticulorden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }

            // $this->updateAutMedicamentos($request, $type);
        } elseif ($request->type == 'Servicios') {
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $auth) {
                $cuporden = Cuporden::where('id', $auth['id'])->first();

                $cuporden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Cuporden_id', $cuporden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Cuporden_id" => $cuporden->id,
                        "Usuario_id"  => auth()->id(),
                        "Nota"        => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }
                $msg = 'Actualizó el Cup orden ' . $cuporden->id . ' a estado Negado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Cuporden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $cuporden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }
            // $this->updateAutServicios($request, $type);
        } elseif($request->type == 'oncologia'){
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $codigo){

                $detaarticulordens = Detaarticulorden::where('Orden_id', $codigo["id"])->get();
                foreach ($detaarticulordens as $detaarticulorden){
                    $codesumi = Codesumi::find($detaarticulorden->codesumi_id);

                    $detaarticulorden->update([
                        'Estado_id' => $state,
                    ]);

                    $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                        ->first();

                    if (!isset($autorizacion)) {
                        Autorizacion::create([
                            "Articulorden_id" => $detaarticulorden->id,
                            "Usuario_id"      => auth()->id(),
                            "Nota"            => $request->observaciones,
                        ]);
                    } else {
                        $autorizacion->update([
                            "Usuario_id" => auth()->id(),
                            "Nota"       => $request->observaciones,
                        ]);
                    }


                    $msg = 'Actualizó el articulo ' . $codesumi->Nombre . ' a estado Aprobado';

                    Auditoria::create([
                        'Descripcion'        => $msg,
                        'Tabla'              => 'Detaarticulorden',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id'           => $detaarticulorden->id,
                        'Motivo'             => $request->observaciones,
                    ]);


                }
            }

        }

        return response()->json('Autorización en estado Inadecuada de manera exitosa', 200);
    }

    public function autorizacionStateAnu(Request $request)
    {
        $type = 'Anulado';

        if ($request->type == 'Medicamentos') {
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $auth) {
                $detaarticulorden = Detaarticulorden::where('id', $auth['id'])->first();

                $detaarticulorden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Articulorden_id" => $detaarticulorden->id,
                        "Usuario_id"      => auth()->id(),
                        "Nota"            => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el articulo ' . $auth['Nombre'] . ' a estado Anulada';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Detaarticulorden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $detaarticulorden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }

            // $test = $this->updateAutMedicamentos($request, $type);
        } elseif ($request->type == 'Servicios') {
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $auth) {
                $cuporden = Cuporden::where('id', $auth['id'])->first();

                $cuporden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Cuporden_id', $cuporden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Cuporden_id" => $cuporden->id,
                        "Usuario_id"  => auth()->id(),
                        "Nota"        => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el Cup orden ' . $cuporden->id . ' a estado Anulada';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Cuporden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $cuporden->id,
                    'Motivo'             => $request->observaciones,
                ]);

                // $this->updateAutServicios($request, $type);
            }

            return response()->json('Autorización modificada de manera exitosa', 200);

        } elseif($request->type == 'oncologia'){
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $codigo){

                $detaarticulordens = Detaarticulorden::where('Orden_id', $codigo["id"])->get();
                foreach ($detaarticulordens as $detaarticulorden){
                    $codesumi = Codesumi::find($detaarticulorden->codesumi_id);

                    $detaarticulorden->update([
                        'Estado_id' => $state,
                    ]);

                    $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                        ->first();

                    if (!isset($autorizacion)) {
                        Autorizacion::create([
                            "Articulorden_id" => $detaarticulorden->id,
                            "Usuario_id"      => auth()->id(),
                            "Nota"            => $request->observaciones,
                        ]);
                    } else {
                        $autorizacion->update([
                            "Usuario_id" => auth()->id(),
                            "Nota"       => $request->observaciones,
                        ]);
                    }


                    $msg = 'Actualizó el articulo ' . $codesumi->Nombre . ' a estado Aprobado';

                    Auditoria::create([
                        'Descripcion'        => $msg,
                        'Tabla'              => 'Detaarticulorden',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id'           => $detaarticulorden->id,
                        'Motivo'             => $request->observaciones,
                    ]);


                }
            }
        }
        return response()->json('Autorización en estado Anulada de manera exitosa', 200);

    }

    public function updateMedicamento(Detaarticulorden $detaarticulorden, Request $request)
    {

        $Motivo = 'Se actualiza la cantidad de ' . $detaarticulorden->Cantidadpormedico . ' a ' . $request->Cantidad_Medico;

        $detaarticulorden->update([
            'NumMeses'                  => $request->Num_Meses,
            'Cantidadpormedico'         => $request->Cantidad_Medico,
            'Cantidadmensualdisponible' => $request->Cantidad_Medico,
        ]);

        $msg = 'Actualizó la cantidad del Articulorden_id ' . $detaarticulorden->id;

        Auditoria::create([
            'Descripcion'        => $msg,
            'Tabla'              => 'Detaarticulorden',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $detaarticulorden->id,
            'Motivo'             => $Motivo,
        ]);

        return response()->json([
            'message' => 'Detalle Articulo Orden',
        ], 201);
    }

    public function updateServicio(Cuporden $cuporden, Request $request)
    {

        $Motivo = 'Se actualiza la cantidad de ' . $cuporden->Cantidad . ' a ' . $request->Cup_Cantidad;

        $cuporden->update([
            'Cantidad' => $request->Cup_Cantidad,
        ]);

        $msg = 'Actualizó la cantidad del Cuporden_id ' . $cuporden->id;

        Auditoria::create([
            'Descripcion'        => $msg,
            'Tabla'              => 'Cuporden',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $cuporden->id,
            'Motivo'             => $Motivo,
        ]);

        return response()->json([
            'message' => 'Cup Orden',
        ], 201);
    }

    public function updateServicioPrestador(Cuporden $cuporden, Request $request)
    {

        $valor_tarifa = $this->getTarifa($request);

        if (!empty($valor_tarifa)) {

            if ($valor_tarifa->Valor > 0) {

                $cuporden->update([
                    'Ubicacion_id' => $request->Sede_Id,
                    'valor_tarifa' => $valor_tarifa->Valor,
                    'valor_total'  => $valor_tarifa->Valor * $request->Cup_Cantidad,
                ]);

                $msg = 'Actualizó el prestador del Cuporden_id ' . $cuporden->id;

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Cuporden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $cuporden->id,
                    'Motivo'             => $request->Motivo,
                ]);

                return response()->json([
                    'message' => 'Cup Orden',
                ], 201);

            }
        }

        return response()->json([
            'message' => 'No se puede actualizar el prestador',
        ], 401);
    }

    public function updateServicioPropioPrestador(Cuporden $cuporden, Request $request)
    {

        // $valor_tarifa = $this->getTarifa($request);
        $codesumi = Cuporden::select(['cp.Codesumi_id as id'])->join('codepropios as cp', 'cp.id', 'cupordens.Serviciopropio_id')->where('cupordens.id', $cuporden->id)->first();

        $checkSede = Sedeproveedore::select(['sedeproveedores.id', 'cpsp.Valor'])
            ->join('code_propio_sede_prestador as cpsp', 'cpsp.sedeproveedor_id', 'sedeproveedores.id')
            ->join('codepropios as cp', 'cp.id', 'cpsp.codepropio_id')
            ->where('cp.Codesumi_id', $codesumi->id)
            ->where('sedeproveedores.id', $request->Sede_Id)
            ->first();

        if (isset($checkSede)) {
            $cuporden->update([
                'Ubicacion_id' => $checkSede->id,
                'valor_tarifa' => $checkSede->Valor,
                'valor_total'  => $checkSede->Valor * $request->Cup_Cantidad,
            ]);

            $msg = 'Actualizó el prestador del Cuporden_id ' . $cuporden->id;

            Auditoria::create([
                'Descripcion'        => $msg,
                'Tabla'              => 'Cuporden',
                'Usuariomodifica_id' => auth()->user()->id,
                'Model_id'           => $cuporden->id,
                'Motivo'             => $request->Motivo,
            ]);

            return response()->json([
                'message' => 'Cup Orden',
            ], 201);

        }

        return response()->json([
            'message' => 'No se puede actualizar el prestador',
        ], 401);
    }

    public function getExcelForMedicamentos(Request $request)
    {
        $detaarticulorden = Detaarticulorden::select(
            'Detaarticulordens.id as id',
            'Detaarticulordens.created_at as FechaOrdenamiento',
            'Detaarticulordens.Cantidadosis as Cantidad_Dosis',
            'Detaarticulordens.codesumi_id as Codesumi_id',
            'Detaarticulordens.Via as Via',
            'Detaarticulordens.Unidadmedida as Unidad_Medida',
            'Detaarticulordens.Frecuencia as Frecuencia',
            'Detaarticulordens.Unidadtiempo as Unidad_Tiempo',
            'Detaarticulordens.Duracion as Duracion',
            'Detaarticulordens.Cantidadmensual as Cantidad_Mensual',
            'Detaarticulordens.Nummeses as Num_Meses',
            'Detaarticulordens.Observacion as Observacion',
            'Detaarticulordens.Cantidadpormedico as Cantidad_Medico',
            'Detaarticulordens.Orden_id as Orden_id',
            'Detaarticulordens.Fechaorden as Fecha_Orden',
            'Detaarticulordens.Estado_id as Estado_id',
            'Users.name as User_Name',
            'Users.apellido as User_LastName',
            'Pacientes.Departamento',
            'Pacientes.created_at',
            'Pacientes.Primer_Nom as Primer_Nom',
            'Pacientes.SegundoNom as Segundo_Nom',
            'Pacientes.Primer_Ape as Primer_Ape',
            'Pacientes.Segundo_Ape as Segundo_Ape',
            'Pacientes.Tipo_Doc as Tipo_Doc',
            'Pacientes.Num_Doc as Cedula',
            'Pacientes.Ut',
            'Pacientes.Edad_Cumplida as Edad_Cumplida',
            'Pacientes.Sexo as Sexo',
            'Pacientes.IPS as IpsAtencion',
            'Pacientes.Direccion_Residencia as Direccion_Residencia',
            'Pacientes.Correo1 as Correo',
            'Pacientes.Telefono as Telefono',
            'Pacientes.Tipo_Afiliado as Tipo_Afiliado',
            'Pacientes.Estado_Afiliado as Estado_Afiliado',
            'Municipios.Nombre as Municipio',
            'cita_paciente.id as citaPaciente_id',
            'codesumis.Nombre as Nombre',
            'codesumis.Codigo as Codigo',
            'codesumis.Requiere_autorizacion as Requiere_Autorizacion',
            'sedeproveedores.Nombre as Nombre_IPS',
            'sedeproveedores.Direccion as Direccion_IPS',
            'sedeproveedores.Telefono1 as Telefono_IPS',
            'cie10s.Codigo_CIE10 as Codigo_CIE10',
            'cie10s.Descripcion as Descripcion_CIE10',
            'autorizacions.Nota as Auth_Nota',
            'autorizacions.updated_at as FechaAutorizacion',
            'u.name as Auth_Name',
            'u.apellido as Auth_Apellido'
        )
            ->leftJoin('Ordens', 'Detaarticulordens.Orden_id', 'Ordens.id')
            ->leftJoin('autorizacions', 'Detaarticulordens.id', 'autorizacions.Articulorden_id')
            ->leftJoin('Users', 'Ordens.Usuario_id', 'Users.id')
            ->leftJoin('Users as u', 'autorizacions.Usuario_id', 'u.id')
            ->leftJoin('cita_paciente', 'Ordens.Cita_id', 'cita_paciente.id')
            ->leftJoin('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
            ->leftJoin('Municipios', 'Pacientes.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, Municipios.id)"))
            ->leftJoin('sedeproveedores', 'Pacientes.IPS',  DB::raw("CONVERT(VARCHAR, sedeproveedores.id)"))
            ->leftJoin('codesumis', 'Detaarticulordens.Codesumi_id', 'codesumis.id')
            ->leftJoin('cie10pacientes', 'cie10pacientes.Citapaciente_id', 'cita_paciente.id')
            ->leftJoin('cie10s', 'cie10s.id', 'cie10pacientes.Cie10_id')
            ->where('Detaarticulordens.Estado_id', $request->status)
            ->whereBetween('Detaarticulordens.Fechaorden', [$request->fechaStart, $request->fechaEnd])

            ->get();

        return response()->json($detaarticulorden, 200);
    }

    public function getExcelForServicios(Request $request)
    {
        $cuporden = Cuporden::select(
            'Cupordens.id as id',
            'Cupordens.created_at as FechaOrdenamiento',
            'Cupordens.Cup_id as Cup',
            'Cupordens.Cantidad as Cup_Cantidad',
            'Cupordens.Orden_id as Orden_id',
            'Cupordens.Observacionorden as observaciones',
            'Cupordens.Estado_id as Estado_id',
            'Users.name as User_Name',
            'Users.apellido as User_LastName',
            'Pacientes.Departamento',
            'Pacientes.created_at',
            'Pacientes.Primer_Nom as Primer_Nom',
            'Pacientes.SegundoNom as Segundo_Nom',
            'Pacientes.Primer_Ape as Primer_Ape',
            'Pacientes.Segundo_Ape as Segundo_Ape',
            'Pacientes.Tipo_Doc as Tipo_Doc',
            'Pacientes.Num_Doc as Cedula',
            'Pacientes.Ut',
            'Pacientes.Edad_Cumplida as Edad_Cumplida',
            'Pacientes.Sexo as Sexo',
            'Pacientes.IPS as IpsAtencion',
            'Pacientes.Direccion_Residencia as Direccion_Residencia',
            'Pacientes.Correo1 as Correo',
            'Pacientes.Telefono as Telefono',
            'Pacientes.Tipo_Afiliado as Tipo_Afiliado',
            'Pacientes.Estado_Afiliado as Estado_Afiliado',
            'Municipios.Nombre as Municipio',
            'Cups.id as Cup_Id',
            'Cups.Codigo as Cup_Codigo',
            'Cups.Nombre as Cup_Nombre',
            'familias.Nombre as FamiliaCup',
            'Sedeproveedores.Nombre as Sede_Nombre',
            'sedeproveedores.Direccion as Sede_Direccion',
            'sedeproveedores.Telefono1 as Sede_Telefono',
            'sede_paciente.Nombre as Nombre_IPS',
            'cita_paciente.id as citaPaciente_id',
            'cie10s.Codigo_CIE10 as Codigo_CIE10',
            'cie10s.Descripcion as Descripcion_CIE10',
            'autorizacions.Nota as Auth_Nota',
            'u.name as User_Autoriza',
            'u.apellido as User_LastName_Autoriza',
            'autorizacions.updated_at as FechaAutorizacion'
        )
            ->join('Ordens', 'Cupordens.Orden_id', 'Ordens.id')
            ->leftJoin('autorizacions', 'Cupordens.id', 'autorizacions.Cuporden_id')
            ->leftjoin('Users as u','u.id','autorizacions.Usuario_id')
            ->join('Users', 'Ordens.Usuario_id', 'Users.id')
            ->join('cita_paciente', 'Ordens.Cita_id', 'cita_paciente.id')
            ->join('Cups', 'Cups.id', 'Cupordens.Cup_id')
            ->join('cupfamilias','Cups.id','cupfamilias.Cup_id')
            ->join('familias','familias.id','cupfamilias.Familia_id')
            ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
            ->leftjoin('Municipios', 'Pacientes.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, Municipios.id)"))
            ->leftJoin('cie10pacientes', 'cie10pacientes.Citapaciente_id', 'cita_paciente.id')
            ->leftJoin('cie10s', 'cie10s.id', 'cie10pacientes.Cie10_id')
            ->leftJoin('Sedeproveedores', 'Cupordens.Ubicacion_id',  DB::raw("CONVERT(VARCHAR, Sedeproveedores.id)"))
            ->leftJoin('Sedeproveedores as sede_paciente', 'Pacientes.IPS', DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
            ->where('Cupordens.Estado_id', $request->status)
            ->where('familias.Tipofamilia_id',6)
            ->whereBetween('Cupordens.created_at', [$request->fechaStart, $request->fechaEnd])
            ->get();

        return response()->json($cuporden, 200);
    }

    private function getTarifa($request)
    {
        $entidadPaciente = Paciente::select('entidad_id')
            ->where('id', $request->paciente)
            ->first();

        $tarifas = Tarifario::select('tarifarios.*')
            ->join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
            ->where('tarifarios.Estado_id', 1)
            ->where('tarifarios.Cup_id', $request->Cup_Id)
            ->where('contratos.Estado_id', 1)
            ->where('contratos.Sedeproveedor_id', $request->Sede_Id)
            ->where('contratos.entidad_id', $entidadPaciente->entidad_id)
            ->first();

        return $tarifas;
    }

    public function calcularFechaOrdenPaginacion($orden){
        $date = Carbon::now();
        if($orden->paginacion){
            $paginacion = explode('/',$orden->paginacion);
            for($i = intval($paginacion[0]);$i <= intval($paginacion[1]);$i++){
                $anterior = Orden::where('Cita_id',$orden->Cita_id)->where('paginacion',($i-1)."/".$paginacion[1])->where('autorizacion',$orden->autorizacion)->first();
                $o = Orden::where('Cita_id',$orden->Cita_id)->where('paginacion',$i."/".$paginacion[1])->where('autorizacion',$orden->autorizacion)->first();
                if($anterior){
                    $fechaAnterior = Carbon::parse($anterior->Fechaorden);
                    $o->update([
                        'Fechaorden' => $fechaAnterior->addDays(28)
                    ]);
                }else{
                    $o->update([
                        'Fechaorden' => $date->addDays(0)
                    ]);
                }
            }
        }else{
            $orden->update(['Fechaorden' => $date->toDateTimeString()]);
        }
    }
    public function contadorSeguimiento($prestadorId){
        $PorGestionar = Cuporden::
            join('sedeproveedores','sedeproveedores.id','cupordens.Ubicacion_id')
            ->where('cupordens.cancelacion')
            ->whereIn('cupordens.Estado_id',[1,7,24])
            ->where('sedeproveedores.Prestador_id',$prestadorId)
            ->count();

        $Canceladas = Cuporden::
        join('sedeproveedores','sedeproveedores.id','cupordens.Ubicacion_id')
            ->where('cupordens.cancelacion','SI')
            ->whereIn('cupordens.Estado_id',[1,7,24])
            ->where('sedeproveedores.Prestador_id',$prestadorId)
            ->count();

        $Asistencia = Cuporden::
        join('sedeproveedores','sedeproveedores.id','cupordens.Ubicacion_id')
            ->where('cupordens.cancelacion','NO')
            ->whereIn('cupordens.Estado_id',[1,7,24])
            ->where('sedeproveedores.Prestador_id',$prestadorId)
            ->count();

        $inasistencia = Cuporden::
        join('sedeproveedores','sedeproveedores.id','cupordens.Ubicacion_id')
            ->where('cupordens.cancelacion','INASISTENCIA')
            ->whereIn('cupordens.Estado_id',[1,7,24])
            ->where('sedeproveedores.Prestador_id',$prestadorId)
            ->count();

        return response()->json([
            'PorGestionar'=>$PorGestionar,
            'Canceladas'=>$Canceladas,
            'Asistencia'=>$Asistencia,
            'inasistencia'=>$inasistencia,
            ], 200);


    }

    public function exportarServiciosPrestador(Request $request){
        $excel = array_map(
            function($data) {
                $array = ["NUMERO"=>$data["idServicio"],
                    "NUMERO ORDEN"=>$data["id"],
                    "NUMERO IDENTIFICACION"=>$data["Cedula"],
                    "CONTRATO"=>$data["Ut"],
                    "NOMBRES"=>$data["Primer_Nom"]." ".$data["Segundo_Nom"],
                    "APELLIDOS" =>$data["Primer_Ape"]." ".$data["Segundo_Ape"],
                    "IPS PRIMARIA" => $data["Prestadores"],
                    "DIRECCION" => $data["Direccion_Residencia"],
                    "CORREO" => $data["Correo"],
                    "TELEFONO" => $data["Telefono"],
                    "CELULAR" => $data["Celular"],
                    "DIAGNOSTICO" => $data["Codigo_CIE10"],
                    "CUP" =>$data["Cup_Codigo"],
                    "ESTADO AUDITORIA" => $data['Estado'],
                    "NOTA AUDITORIA" => $data['Auth_Nota'],
                    "DESCRIPCION CUP" =>$data["Cup_Nombre"],
                    "FECHA ORDEN" => $data["Fechaorden"],
                    "FECHA TOMA" => "",
                    "FECHA RESULTADO" => ""
                ];
                return $array;
            },
            $request->all()
        );
        return (new FastExcel($excel))->download('file.xls');
    }

    public function autorizacionStateAprobOne(Request $request)
    {
        $type = 'Aprobado';

        if ($request->type == 'Medicamentos') {
            $state = $this->getState($type);
            foreach ($request->autorizaciones as $auth) {
                $detaarticulorden = Detaarticulorden::where('id', $auth['id'])->first();

                $detaarticulorden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Articulorden_id" => $detaarticulorden->id,
                        "Usuario_id"      => auth()->id(),
                        "Nota"            => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el articulo ' . $auth['Nombre'] . ' a estado Aprobado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Detaarticulorden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $detaarticulorden->id,
                    'Motivo'             => $request->observaciones,
                ]);
                $orden = Orden::find($detaarticulorden->Orden_id);
                $this->calcularFechaOrdenPaginacion($orden);
            }
        } elseif ($request->type == 'Servicios') {
            $state = $this->getState($type);
            $idOrden = 0;
            foreach ($request->autorizaciones as $auth) {
                $idOrden = $auth["Orden_id"];

                $cuporden = Cuporden::where('id', $auth['id'])->first();

                $cuporden->update([
                    'Estado_id' => $state,
                ]);

                $autorizacion = Autorizacion::where('Cuporden_id', $cuporden->id)
                    ->first();

                if (!isset($autorizacion)) {
                    Autorizacion::create([
                        "Cuporden_id" => $cuporden->id,
                        "Usuario_id"  => auth()->id(),
                        "Nota"        => $request->observaciones,
                    ]);
                } else {
                    $autorizacion->update([
                        "Usuario_id" => auth()->id(),
                        "Nota"       => $request->observaciones,
                    ]);
                }

                $msg = 'Actualizó el Cup orden ' . $cuporden->id . ' a estado Aprobado';

                Auditoria::create([
                    'Descripcion'        => $msg,
                    'Tabla'              => 'Cuporden',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $cuporden->id,
                    'Motivo'             => $request->observaciones,
                ]);
            }
            $orden = Orden::find($idOrden);
            $this->calcularFechaOrdenPaginacion($orden);
        } elseif($request->type == 'oncologia'){
            $state = $this->getState($type);

            foreach ($request->autorizaciones as $codigo){
                $orden = Orden::find($codigo["id"]);
                $orden->update([
                    'Estado_id' => 1,
                    'Fechaorden' => $request->fechaAutorizacion
                ]);
                $detaarticulordens = Detaarticulorden::where('Orden_id', $codigo["id"])->get();
                foreach ($detaarticulordens as $detaarticulorden){
                    $codesumi = Codesumi::find($detaarticulorden->codesumi_id);

                    $detaarticulorden->update([
                        'Estado_id' => $state,
                    ]);

                    $autorizacion = Autorizacion::where('Articulorden_id', $detaarticulorden->id)
                        ->first();

                    if (!isset($autorizacion)) {
                        Autorizacion::create([
                            "Articulorden_id" => $detaarticulorden->id,
                            "Usuario_id"      => auth()->id(),
                            "Nota"            => $request->observaciones,
                        ]);
                    } else {
                        $autorizacion->update([
                            "Usuario_id" => auth()->id(),
                            "Nota"       => $request->observaciones,
                        ]);
                    }


                    $msg = 'Actualizó el articulo ' . $codesumi->Nombre . ' a estado Aprobado';

                    Auditoria::create([
                        'Descripcion'        => $msg,
                        'Tabla'              => 'Detaarticulorden',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id'           => $detaarticulorden->id,
                        'Motivo'             => $request->observaciones,
                    ]);


                }
            }

        }

        return response()->json('Autorización Aprobada de manera exitosa', 200);
    }
    public function getOrdenesPortabilidad(Request $request)
    {
        $ordereds = Orden::select([
            'ordens.id',
            'ordens.created_at',
            'ordens.Cita_id',
            'ordens.Tiporden_id',
            'u.name as User_Name',
            'u.apellido as User_LastName',
            'u.Firma as Medico_Firma',
            'cp.id as citaPaciente_id',
            'p.id as Paciente_id',
            'p.Departamento',
            'p.created_at as paciente_created',
            'p.Primer_Nom as Primer_Nom',
            'p.SegundoNom as Segundo_Nom',
            'p.Primer_Ape as Primer_Ape',
            'p.Segundo_Ape as Segundo_Ape',
            'p.id as Paciente_id',
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
            'sede_paciente.Nombre as Nombre_IPS'])
            ->join('users as u', 'ordens.Usuario_id', 'u.id')
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->leftJoin('municipios as m', 'p.Mpio_Atencion', DB::raw("CONVERT(VARCHAR, m.id)"))
            ->leftJoin('sedeproveedores as sede_paciente', 'p.IPS',  DB::raw("CONVERT(VARCHAR, sede_paciente.id)"))
            ->with([
                'cupordens'           => function ($query) use ($request) {
                    $query->select(
                        'Cupordens.id',
                        'Cupordens.created_at as FechaOrdenamiento',
                        'Cupordens.Cantidad as Cup_Cantidad',
                        'Cupordens.Orden_id as Orden_id',
                        'Cupordens.Observacionorden as observaciones',
                        'Cupordens.Estado_id as Estado_id',
                        'c.id as Servicio_Id',
                        'c.Nombre as Servicio_Nombre',
                        'c.Codigo as Cup_Codigo',
                        's.id as Sede_Id',
                        's.Nombre as Sede_Nombre',
                        's.Direccion as Sede_Direccion',
                        's.Telefono1 as Sede_Telefono'
                    )
                        ->join('cups as c', 'Cupordens.Cup_id', 'c.id')
                        ->leftJoin('sedeproveedores as s', 'Cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
                        ->where('Cupordens.Estado_id', 37)
                        ->whereMonth('Cupordens.created_at', $request->month)
                        ->get();
                },
                'citapaciente'        => function ($query) {
                    $query->select(['id']);
                },
                'citapaciente.cie10s' => function ($query) {
                    $query->select(['Descripcion', 'Codigo_CIE10']);
                },
            ])
            ->with([
                'detaarticulordens'           => function ($query) use ($request) {
                    $query->select(
                        'detaarticulordens.id',
                        'detaarticulordens.created_at as FechaOrdenamiento',
                        'detaarticulordens.Cantidadpormedico as Cup_Cantidad',
                        'detaarticulordens.Orden_id as Orden_id',
                        'detaarticulordens.Observacion as observaciones',
                        'detaarticulordens.Estado_id as Estado_id',
                        'c.id as Servicio_Id',
                        'c.Nombre as Servicio_Nombre',
                        'c.Codigo as Cup_Codigo',
                    )
                        ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                        ->where('detaarticulordens.Estado_id', 37)
                        ->whereMonth('detaarticulordens.created_at', $request->month)
                        ->get();
                },
                'citapaciente'        => function ($query) {
                    $query->select(['id']);
                },
                'citapaciente.cie10s' => function ($query) {
                    $query->select(['Descripcion', 'Codigo_CIE10']);
                },
            ])
            ->whereHas('cupordens', function ($query) use ($request) {$query->where('Estado_id', 37)->whereMonth('Cupordens.created_at', $request->month);})
            ->whereHas('detaarticulordens', function ($query) use ($request) {$query->where('Estado_id', 37)->whereMonth('detaarticulordens.created_at', $request->month);})
            ->where('p.portabilidad',1)
            ->where('p.Estado_Afiliado',1)
            ->get();

        return response()->json($ordereds, 200);
    }

    public function visionTotal(Request $request)
        {
            $result = [];
            $appointments = Collect(DB::select("SET NOCOUNT ON exec dbo.SP_Ordenamientos_Vision_Total ?,?,?",[$request->fechaDesde, $request->fechaHasta,$request->entidad]));
            $result = json_decode($appointments, true);
            return (new FastExcel($result))->download('file.xls');
        }

}
