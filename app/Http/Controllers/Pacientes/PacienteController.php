<?php

namespace App\Http\Controllers\Pacientes;

use App\Events\NotificacionEvent;
use App\Modelos\ResultadosLaboratorio;
use App\User;
use DateTime;
use Carbon\Carbon;
use App\Modelos\Cita;
use App\Modelos\Tipo;
use App\Modelos\Orden;
use App\Modelos\Estado;
use App\Modelos\Cuporden;
use App\Modelos\Entidade;
use App\Modelos\Paciente;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Examenfisico;
use App\Modelos\Incapacidade;
use App\Modelos\Orden_cabecera;
use App\Modelos\Sedeproveedore;
use App\Modelos\Detaarticulorden;
use App\Modelos\revision_sarlaft;
use PhpParser\Node\Expr\FuncCall;
use App\Modelos\Adjunto_novedades;
use App\Modelos\AuditoriaDescarga;
use App\Modelos\NovedadesPaciente;
use Illuminate\Support\Facades\DB;
use App\Modelos\AuditoriaNovedades;
use App\Http\Controllers\Controller;
use App\Modelos\representante_legal;
use App\Modelos\Resultados_annarlab;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\AntecedenteFarmacologico;
use App\Modelos\barreraAcceso;
use App\Modelos\DemandaInsatisfecha;
use App\Modelos\Registroconcurrencia;
use App\Services\PacienteService;
use Error;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PacienteController extends Controller
{
    public function all()
    {
        $paciente = Paciente::all();
        return response()->json($paciente, 200);
    }
    public function pacientesEmpalme()
    {
        $paciente = Paciente::whereNotNull('ut_saliente')
            ->where('entidad_id', 3)
            ->get();
        return response()->json($paciente, 200);
    }

    public function citaPendiente(Paciente $paciente)
    {
        $citapendiente = Cita::where('paciente_id', $paciente->id)->first();
        return response()->json($citapendiente, 200);
    }

    public function store(Request $request)
    {
        $mensaje = 'El paciente ya existe!';
        $estado = true;
        $paciente = Paciente::where('Num_Doc', $request->Num_Doc)->first();
        if (!$paciente) {
            // $hoy =new DateTime();
            // $años = $hoy->diff(new DateTime($request->Fecha_Naci));
            // $fechanaci = substr($request->Fecha_Naci, 0, 10);
            Paciente::create([
                'Region' => $request->Region,
                'Ut' => $request->Ut,
                'Primer_Nom' => $request->Primer_Nom,
                'SegundoNom' => $request->SegundoNom,
                'Primer_Ape' => $request->Primer_Ape,
                'Segundo_Ape' => $request->Segundo_Ape,
                'Tipo_Doc' => $request->Tipo_Doc,
                'Num_Doc' => $request->Num_Doc,
                'Sexo' => $request->Sexo,
                'Fecha_Afiliado' => $request->Fecha_Afiliado,
                'Fecha_Naci' => $request->Fecha_Naci,
                'Edad_Cumplida' => $request->Edad_Cumplida,
                'Discapacidad' => $request->Discapacidad,
                'Grado_Discapacidad' => $request->Grado_Discapacidad,
                'Tipo_Afiliado' => $request->Tipo_Afiliado,
                'Orden_Judicial' => $request->Orden_Judicial,
                'Num_Folio' => $request->Num_Folio,
                'Fecha_Emision' => $request->Fecha_Emision,
                'Parentesco' => $request->Parentesco,
                'TipoDoc_Cotizante' => $request->TipoDoc_Cotizante,
                'Doc_Cotizante' => $request->Doc_Cotizante,
                'Tipo_Cotizante' => $request->Tipo_Cotizante,
                'Estado_Afiliado' => $request->Estado_Afiliado,
                'Dane_Mpio' => $request->Dane_Mpio,
                'Mpio_Afiliado' => $request->Mpio_Afiliado,
                'Dane_Dpto' => $request->Dane_Dpto,
                'Departamento' => $request->Departamento,
                'Subregion' => $request->Subregion,
                'Dpto_Atencion' => $request->Dpto_Atencion,
                'Mpio_Atencion' => $request->Mpio_Atencion,
                'IPS' => $request->IPS,
                'Sede_Odontologica' => $request->Sede_Odontologica,
                'Medicofamilia' => $request->Medicofamilia,
                'Etnia' => $request->Etnia,
                'Nivel_Sisben' => $request->Nivel_Sisben,
                'Laboraen' => $request->Laboraen,
                'Mpio_Labora' => $request->Mpio_Labora,
                'Direccion_Residencia' => $request->Direccion_Residencia,
                'Mpio_Residencia' => $request->Mpio_Residencia,
                'Telefono' => $request->Telefono,
                'Correo1' => $request->Correo1,
                'Correo2' => $request->Correo2,
                'Estrato' => $request->Estrato,
                'Celular1' => $request->Celular1,
                'Celular2' => $request->Celular2,
                'Sexo_Biologico' => $request->Sexo_Biologico,
                'Tipo_Regimen' => $request->Tipo_Regimen,
                'Num_Hijos' => $request->Num_Hijos,
                'Vivecon' => $request->Vivecon,
                'RH' => $request->RH,
                'Tienetutela' => $request->Tienetutela,
                'Nivelestudio' => $request->Nivelestudio,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
                'Nombreacompanante' => $request->Nombreacompanante,
                'Telefonoacompanante' => $request->Telefonoacompanante,
                'Nombreresponsable' => $request->Nombreresponsable,
                'Telefonoresponsable' => $request->Telefonoresponsable,
                'Aseguradora' => $request->Aseguradora,
                'Tipovinculacion' => $request->Tipovinculacion,
                'Ocupacion' => $request->Ocupacion,
                'nivel' => $request->nivel,
                'entidad_id' => $request->entidad_id,
                'vlr_upc' => $request->vlr_upc,
                'fecha_ini_cont' => $request->fecha_ini_cont,
                'fecha_fin_cont' => $request->fecha_fin_cont,
                'valor_cont_cap' => $request->valor_cont_cap,
                'valot_cont_pyp' => $request->valot_cont_pyp,
                'sem_cot' => $request->sem_cot,
                'tipo_categoria' => $request->tipo_categoria,
                'ut_saliente' => $request->ut_saliente,
                'estado_civil' => $request->estado_civil,
                'dx' => $request->dx,
                'cups' => $request->cups,
                'cums' => $request->cums,
                'propios' => $request->propios,
                'f_solicitud' => $request->f_solicitud,
                'anexo' => $request->anexo,
                'ruta' => $request->ruta,
                'represa' => $request->represa,
                'justifica_represa' => $request->justifica_represa,
                'observacion_contratista' => $request->observacion_contratista,
                'cargo_laboral' => $request->cargo_laboral,
                'composicion_familiar' => $request->composicion_familiar,
                'vivienda' => $request->vivienda,
                'personas_a_cargo' => $request->personas_a_cargo,
                'tipo_contratacion' => $request->tipo_contratacion,
                'antiguedad_en_empresa' => $request->antiguedad_en_empresa,
                'antiguedad_cargo_actual' => $request->antiguedad_cargo_actual,
                'numero_cursos_a_cargo' => $request->numero_cursos_a_cargo,
            ]);
            $mensaje = 'Paciente creado con Exito!';
            $estado = false;
        }
        return response()->json(['mensaje' => $mensaje, 'estado' => $estado]);
    }

    public function guardarPaciente(Request $request)
    {
        $mensaje = 'El paciente ya existe!';
        $estado = true;
        $paciente = Paciente::where('Num_Doc', $request->Num_Doc)->first();
        if (!$paciente) {

            if($request->Fecha_Naci != null || $request->Fecha_Naci != ''){
                $hoy =new DateTime();
                $calculoanios = $hoy->diff(new DateTime($request->Fecha_Naci));
                $anios = $calculoanios->y;
                $fechanaci = date("d/m/Y", strtotime($request->Fecha_Naci));
            }else{
                $fechanaci = null;
                $anios = null;
            }

            Paciente::create([

                'Region' => $request->Region,
                'Ut' => $request->Ut,
                'Primer_Nom' => strtoupper($request->Primer_Nom),
                'SegundoNom' => ($request->SegundoNom == '' ? null : strtoupper($request->SegundoNom)),
                'Primer_Ape' => strtoupper($request->Primer_Ape),
                'Segundo_Ape' => ($request->Segundo_Ape == '' ? null : strtoupper($request->Segundo_Ape)),
                'Tipo_Doc' => $request->Tipo_Doc,
                'Num_Doc' => $request->Num_Doc,
                'Sexo' => $request->Sexo,
                'Fecha_Afiliado' => $request->Fecha_Afiliado,
                'Fecha_Naci' => $fechanaci,
                'Edad_Cumplida' => $anios,
                'Discapacidad' => $request->Discapacidad,
                'Grado_Discapacidad' => $request->Grado_Discapacidad,
                'Tipo_Afiliado' => $request->Tipo_Afiliado,
                'Orden_Judicial' => $request->Orden_Judicial,
                'Num_Folio' => $request->Num_Folio,
                'Fecha_Emision' => $request->Fecha_Emision,
                'Parentesco' => $request->Parentesco,
                'TipoDoc_Cotizante' => $request->TipoDoc_Cotizante,
                'Doc_Cotizante' => $request->Doc_Cotizante,
                'Tipo_Cotizante' => $request->Tipo_Cotizante,
                'Estado_Afiliado' => $request->Estado_Afiliado,
                'Dane_Mpio' => $request->Dane_Mpio,
                'Mpio_Afiliado' => $request->Mpio_Afiliado,
                'Dane_Dpto' => $request->Dane_Dpto,
                'Departamento' => $request->Departamento,
                'Subregion' => $request->Subregion,
                'Dpto_Atencion' => $request->Dpto_Atencion,
                'Mpio_Atencion' => $request->Mpio_Atencion,
                'IPS' => $request->IPS,
                'Sede_Odontologica' => $request->Sede_Odontologica,
                'Medicofamilia' => $request->Medicofamilia,
                'Etnia' => $request->Etnia,
                'Nivel_Sisben' => $request->Nivel_Sisben,
                'Laboraen' => $request->Laboraen,
                'Mpio_Labora' => $request->Mpio_Labora,
                'Direccion_Residencia' => $request->Direccion_Residencia,
                'Mpio_Residencia' => $request->Mpio_Residencia,
                'Telefono' => $request->Telefono,
                'Correo1' => $request->Correo1,
                'Correo2' => $request->Correo2,
                'Estrato' => $request->Estrato,
                'Celular1' => $request->Celular1,
                'Celular2' => $request->Celular2,
                'Sexo_Biologico' => $request->Sexo_Biologico,
                'Tipo_Regimen' => $request->Tipo_Regimen,
                'Num_Hijos' => $request->Num_Hijos,
                'Vivecon' => $request->Vivecon,
                'RH' => $request->RH,
                'Tienetutela' => $request->Tienetutela,
                'Nivelestudio' => $request->Nivelestudio,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
                'Nombreacompanante' => $request->Nombreacompanante,
                'Telefonoacompanante' => $request->Telefonoacompanante,
                'Nombreresponsable' => $request->Nombreresponsable,
                'Telefonoresponsable' => $request->Telefonoresponsable,
                'Aseguradora' => $request->Aseguradora,
                'Tipovinculacion' => $request->Tipovinculacion,
                'Ocupacion' => $request->Ocupacion,
                'nivel' => $request->nivel,
                'entidad_id' => $request->entidad_id,
                'vlr_upc' => $request->vlr_upc,
                'fecha_ini_cont' => $request->fecha_ini_cont,
                'fecha_fin_cont' => $request->fecha_fin_cont,
                'valor_cont_cap' => $request->valor_cont_cap,
                'valot_cont_pyp' => $request->valot_cont_pyp,
                'sem_cot' => $request->sem_cot,
                'tipo_categoria' => $request->tipo_categoria,
                'ut_saliente' => $request->ut_saliente,
                'estado_civil' => $request->estado_civil,
                'dx' => $request->dx,
                'cups' => $request->cups,
                'cums' => $request->cums,
                'propios' => $request->propios,
                'f_solicitud' => $request->f_solicitud,
                'anexo' => $request->anexo,
                'ruta' => $request->ruta,
                'represa' => $request->represa,
                'justifica_represa' => $request->justifica_represa,
                'observacion_contratista' => $request->observacion_contratista,
                'cargo_laboral' => $request->cargo_laboral,
                'composicion_familiar' => $request->composicion_familiar,
                'vivienda' => $request->vivienda,
                'personas_a_cargo' => $request->personas_a_cargo,
                'tipo_contratacion' => $request->tipo_contratacion,
                'antiguedad_en_empresa' => $request->antiguedad_en_empresa,
                'antiguedad_cargo_actual' => $request->antiguedad_cargo_actual,
                'numero_cursos_a_cargo' => $request->numero_cursos_a_cargo,
            ]);
            $mensaje = 'Paciente creado con Exito!';
            $estado = false;
        }
        return response()->json(['mensaje' => $mensaje, 'estado' => $estado]);
    }

    public function show($paciente)
    {
        $paciente = Paciente::find($paciente);
        if (!isset($paciente)) {
            return response()->json([
                'message' => 'El Paciente buscado no Existe!'], 404);
        }
        return response()->json($paciente, 200);
    }

    public function showEnabled($cedula)
    {
        $paciente = Paciente::select(
            'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
            'pacientes.Segundo_Ape','pacientes.Tipo_Doc','pacientes.ppp','pacientes.abrazarte','pacientes.latir_sentido',
            'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
            'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
            'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
            'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
            'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
            'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
            'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
            'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
            'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
            'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
            'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
            'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
            'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
            'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
            'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
            'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
            'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
            'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
            'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
            'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
            'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus','sedeproveedores.Nombre as NombreIPS',
            'e.id as entidad_id', DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"))
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->leftjoin('users','users.id','pacientes.Medicofamilia')
            ->where('Num_Doc', $cedula)
            ->where('Estado_Afiliado', 1)
            ->whereIn('entidad_id', [1,3,7])
            ->first();
        if ($paciente) {
            return response()->json([
                'paciente' => $paciente,
            ], 200);
        }else{
            return response()->json([
                'message' => 'El paciente no esta activo',
            ], 200);
        }
    }

    public function pacienteRutacovi($cedula)
    {
        $paciente = Paciente::select('pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
        'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
        'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
        'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
        'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
        'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
        'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
        'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
        'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
        'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
        'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
        'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
        'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
        'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
        'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
        'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
        'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
        'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
        'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
        'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
        'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
        'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
        'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
        'sedeproveedores.Nombre as NombreIPS', 'e.nombre as entidad','rg.estado_id as estadoRegistro',
            DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"))
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->leftjoin('users','users.id','pacientes.Medicofamilia')
            ->leftjoin('registrocovis as rg','Pacientes.id','rg.paciente_id')
            ->where('Num_Doc', $cedula)
            ->where('Estado_Afiliado', 1)
            ->where('entidad_id', '!=', 4)
            ->first();
        if ($paciente) {
            return response()->json([
                'paciente' => $paciente,
            ], 200);
        }else{
            return response()->json([
                'message' => 'El paciente no esta activo o pertenece a otra entidad',
            ], 200);
        }
    }

    public function showCaracterizacion($cedula)
    {
        $paciente = Paciente::select('pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
        'pacientes.Segundo_Ape','pacientes.Tipo_Doc', 'pacientes.parentesco',
        'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
        'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
        'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
        'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
        'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
        'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
        'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
        'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
        'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
        'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
        'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
        'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
        'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
        'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
        'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo', 'pacientes.grado_discapacidad',
        'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
        'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
        'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
        'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
        'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
        'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus', 'pacientes.Mpio_Afiliado',
        'c.id as id_caraterizacion','c.user_id_registra','c.paciente_id','c.alergicas','c.vacunado','c.vacunarse','c.zona_vivienda',
        'c.correo','c.residencia','c.telefono1','c.telefono2','c.correocontacto','c.conforman_hogar',
        'c.tipo_vivienda','c.hogar_con_agua','c.alimentos','c.energia','c.accesibilidad_vivienda',
        'c.seguridad_orden','c.etnia','c.fisica','c.auditiva','c.visual','c.sordera','c.intelectual',
        'c.mental','c.escolaridad','c.estrato','c.orientacion_sexual','c.planificando_metodos',
        'c.planeado_embarazo','c.citologia_ultimo_ano','c.tamizaje_Mamografia','c.tamizaje_Prostata',
        'c.autocuidado_salud','c.victima_violencia','c.victima_desplazamiento','c.exposicion_tabaco',
        'c.tabacos_al_dia','c.expuestotabaco','c.consume_sustancias_psicoactivas','c.Frecuencia_consumo_psicoactivas', 'c.oficio',
        'c.consume_alcohol','c.Frecuencia_consumo_alcohol','c.actividad_fisica','c.mama','c.prostata','c.pulmon',
        'c.gastrointestinales','c.cervicouterino','c.piel','c.otros','c.obesidad','c.tiroides','c.mellitus',
        'c.dislipidemia','c.ansiedad','c.depresion','c.esquizofrenia','c.consumopsicoativo','c.bipolar',
        'c.hiperactividad','c.conductaalimentaria','c.antecedente_mama','c.antecedente_prostata',
        'c.antecedente_pulmon','c.antecedente_gastrointestinales','c.antecedente_cervicouterino',
        'c.antecedente_piel','c.antecedente_otros','c.antecedente_obesidad','c.antecedente_tiroides',
        'c.antecedente_mellitus','c.antecedente_dislipidemia','c.antecedente_cardiopatia',
        'c.antecedente_hipertension','c.antecedente_cerebrovascular','c.antecedente_arterial',
        'c.antecedente_renal','c.antecedente_asma','c.antecedente_epoc','c.antecedente_bronquitis',
        'c.antecedente_apnea','c.antecedenterespiratoria_otros','c.condiciones_vih','c.condiciones_autoinmunes',
        'c.condiciones_biologicos','c.condiciones_quimioterapia','c.condiciones_otros','c.estado_id',
        'c.medicamentos','c.antecedente_hospitalizacion', 'c.antecedente_patologico', 'c.riesgo_individualizado','c.Maternoperinatal','c.salud_mental',
        'c.riesgo_cardiovascular','c.Oncologicos','c.nefroproteccion','c.respiratorios_cronicos','c.reumatologicos',
        'c.trasmisibles_cronicos','c.rehabilitación_integral','c.cuidados_paliativos','c.enfermedades_huerfanas','c.Economia_articular',
        'c.alteraciones_nutricionales','c.enfermedades_infecciosas','c.trastorno_consumo_sustancias_psicoactivas', 'c.parentesco_cuidador',
        'c.enfermedades_cardiovasculares','c.cancer','c.trastornos_visuales','c.trastornos_auditivos','c.trastornos_salud_bucal', 'c.problemas_salud_mental','c.zonóticas',
        'c.accidente_enfermedad_laboral', 'c.trastornos_degenarativos_neuropatias_autoinmune',  'c.violencias', 'c.habitaciones', 'c.grado_discapacidad_uno', 'c.grado_discapacidad_dos',
        'c.grado_discapacidad_tres', 'c.grado_discapacidad_cuatro', 'c.grado_discapacidad_cinco', 'c.estado_Paciente', 'c.cicloVital',
        'e.nombre as entidad','sede.Nombre as NombreIPS')
            ->leftjoin('caracterizacions as c', 'pacientes.id', 'c.paciente_id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->leftjoin('sedeproveedores as sede','sede.id','pacientes.IPS')
            ->where('Num_Doc', $cedula)
            ->first();
        if ($paciente) {
            if ($paciente->id_caraterizacion) {
                return response()->json([
                    'paciente' => $paciente,
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'El paciente no cuenta con Caracterizacion',
                    'paciente' => $paciente,
                ], 200);
            }
        }
    }
    public function verPaciente($cedula)
    {
        $paciente = Paciente::select(
            'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
            'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
            'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
            'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
            'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
            'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
            'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
            'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
            'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
            'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
            'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
            'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
            'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
            'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
            'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
            'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
            'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
            'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
            'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
            'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
            'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
            'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
            'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus', 'd.Nombre as DptoAtencion',
            'users.id as Medicofamilia',
            //DB::raw("CONCAT(users.name,' ',users.apellido) as Medicofamilia"),
            DB::raw("CONCAT(sedeproveedores.Nombre,' ',sedeproveedores.id) as NombreIPS"),
            'e.id as entidad_id')
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->leftjoin('users', 'pacientes.Medicofamilia', 'users.id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->leftjoin('departamentos as d', 'pacientes.Dpto_Atencion', 'd.id')
            ->where('Num_Doc', $cedula)
            ->first();

        return response()->json([
            'paciente' => $paciente,
        ], 200);
    }

    public function validacionDerechos($cedula)
    {
        $paciente = Paciente::where('Num_Doc', $cedula)->first();
        if (!$paciente) {
            return response()->json([
                'message' => 'El paciente no existe en el sistema',
            ], 201);
        }else if($paciente->entidad_id == 4){
            return response()->json([
                'message' => 'El paciente no pertenece al contrato magisterio o ferrocarril',
            ], 201);
        }
        else {
            $pacienteNovedad = Paciente::select(
                'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
                'pacientes.Segundo_Ape','pacientes.Tipo_Doc','pacientes.ppp','pacientes.abrazarte','pacientes.latir_sentido',
                'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
                'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
                'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
                'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
                'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia',DB::raw("CONCAT(users.name,' ',users.apellido) as medico_familia"),'pacientes.Etnia','pacientes.Nivel_Sisben',
                'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
                'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
                'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
                'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
                'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
                'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
                'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
                'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
                'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
                'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
                'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
                'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
                'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
                'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
                'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
                'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
                'sedeproveedores.Nombre as NombreIPS', 'municipios.Nombre as NombreMunicipio',
                'e.nombre as entidad','departamentos.Nombre as NombreDepartamento','sedeproveedores.id as Sedeprestador_id')
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->leftjoin('municipios','municipios.id','pacientes.Mpio_Atencion')
                ->leftjoin('departamentos','departamentos.id','municipios.Departamento_id')
                ->leftjoin('users','users.id','pacientes.Medicofamilia')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->where('pacientes.id', $paciente->id)
                ->first();

            $entidad = Paciente::select(
                'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
                'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
                'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
                'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
                'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
                'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
                'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
                'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
                'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
                'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
                'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
                'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
                'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
                'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
                'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
                'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
                'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
                'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
                'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
                'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
                'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
                'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
                'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
                'sedeproveedores.Nombre as NombreIPS', 'municipios.Nombre as NombreMunicipio',
                'e.nombre as entidad','departamentos.Nombre as NombreDepartamento','estados.Nombre as EstadoPaciente')
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->join('estados','estados.id','pacientes.Estado_Afiliado')
                ->leftjoin('municipios','municipios.id','pacientes.Mpio_Atencion')
                ->leftjoin('departamentos','departamentos.id','municipios.Departamento_id')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->where('pacientes.id', $paciente->id)
                ->get();

            $Novedades = Tipo::select('id as tipoId','Nombre')
                ->where("Descripcion","Novedades")
                ->where("Estado_id",1)->get();

            return response()->json([
                    'paciente' => $pacienteNovedad,
                    'entidad' => $entidad,
                    'Novedades'=>$Novedades]
                , 200);
        }
    }

    public function consultaBeneficiarios($cedula){
        $paciente = Paciente::where('Num_Doc',$cedula)->first();
        $Beneficiario = Paciente::select(
            'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
            'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
            'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
            'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
            'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
            'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
            'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
            'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
            'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
            'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
            'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
            'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
            'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
            'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
            'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
            'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
            'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
            'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
            'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
            'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
            'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
            'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
            'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
            'estados.Nombre as EstadoPaciente','sedeproveedores.Nombre as NombreIPS',
            DB::raw("CONCAT(Pacientes.Primer_Nom,' ',Pacientes.SegundoNom,' ',Pacientes.Primer_Ape,' ',Pacientes.Segundo_Ape) as NombreCompleto"),
            'municipios.Nombre as NombreMunicipio',
            'e.nombre as entidad','departamentos.Nombre as NombreDepartamento')
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->join('estados','estados.id','Pacientes.Estado_Afiliado')
            ->join('municipios','municipios.id','pacientes.Mpio_Atencion')
            ->join('departamentos','departamentos.id','municipios.Departamento_id')
            ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
            ->where('Doc_Cotizante', $cedula);
        if($paciente->Doc_Cotizante){
            $Beneficiario->orWhere('Num_Doc',$paciente->Doc_Cotizante);
        }

        return response()->json($Beneficiario->get(), 200);
    }

    public function consultaComplementarios($cedula){
        $paciente = Paciente::select(
            'examenfisicos.id','examenfisicos.Citapaciente_id','examenfisicos.Peso','examenfisicos.Talla',
            'examenfisicos.Imc','examenfisicos.Clasificacion','examenfisicos.Perimetroabdominal',
            'examenfisicos.Perimetrocefalico','examenfisicos.Frecucardiaca','examenfisicos.Pulsos',
            'examenfisicos.Frecurespiratoria','examenfisicos.Temperatura',
            'examenfisicos.Saturacionoxigeno','examenfisicos.Posicion','examenfisicos.Lateralidad',
            'examenfisicos.Presionsistolica','examenfisicos.Presiondiastolica',
            'examenfisicos.Presionarterialmedia','examenfisicos.Condiciongeneral',
            'examenfisicos.Cabezacuello','examenfisicos.Ojosfondojos','examenfisicos.Agudezavisual',
            'examenfisicos.Cardiopulmonar','examenfisicos.Abdomen','examenfisicos.Osteomuscular',
            'examenfisicos.Extremidades','examenfisicos.Pulsosperifericos','examenfisicos.Neurologico',
            'examenfisicos.Reflejososteotendinos','examenfisicos.Pielfraneras','examenfisicos.Genitourinario',
            'examenfisicos.Examenmama','examenfisicos.Tactoretal','examenfisicos.Examenmental',
            'examenfisicos.ISC','examenfisicos.nota','examenfisicos.tipo','examenfisicos.user_id',
            'examenfisicos.tasa_filtracion','examenfisicos.paciente_id','examenfisicos.cabeza',
            'examenfisicos.checkboxCcc','examenfisicos.cara','examenfisicos.Ojos','examenfisicos.Selccc',
            'examenfisicos.AgudezavisualDer','examenfisicos.AgudezavisualIzq','examenfisicos.Conjuntiva',
            'examenfisicos.Esclera','examenfisicos.OjosfondojosAnt','examenfisicos.OjosfondojosPost',
            'examenfisicos.Nariz','examenfisicos.Tabique','examenfisicos.Cornetes','examenfisicos.Oidos',
            'examenfisicos.AuricularDer','examenfisicos.AuricularIzq','examenfisicos.ConductoDer',
            'examenfisicos.MembranaTim','examenfisicos.integra','examenfisicos.perforacion','examenfisicos.TubosVen',
            'examenfisicos.Maxilar','examenfisicos.LabiosComisura','examenfisicos.MejillaCarrillo',
            'examenfisicos.CavidadOral','examenfisicos.ArticulaciónTemporo','examenfisicos.EstructurasDentales',
            'examenfisicos.checkboxTorax','examenfisicos.Torax','examenfisicos.checkboxDesTorax',
            'examenfisicos.Mamas','examenfisicos.Pectorales','examenfisicos.RejaCostal',
            'examenfisicos.checkboxDesToraxPos','examenfisicos.RejaCostalPos','examenfisicos.DesvCol',
            'examenfisicos.checkboxCardioPulmonar','examenfisicos.Pulmones','examenfisicos.Cardiacos',
            'examenfisicos.checkboxAbdomen','examenfisicos.AlturaUterina','examenfisicos.ActividadUterina',
            'examenfisicos.FrecuenciaCardiacaFetal','examenfisicos.movimientosFetales',
            'examenfisicos.RuidosPlacentarios','examenfisicos.checkboxManiobra','examenfisicos.PresentacionFetal',
            'examenfisicos.NumFetos','examenfisicos.PosUtero','examenfisicos.Tacto',
            'examenfisicos.checkboxGenitoUrinario','examenfisicos.Maculino','examenfisicos.Testiculos',
            'examenfisicos.Escroto','examenfisicos.Prepucio','examenfisicos.Cordon','examenfisicos.TactoRectalHom',
            'examenfisicos.Femenino','examenfisicos.Especuloscopia','examenfisicos.TactoVag','examenfisicos.Involucion',
            'examenfisicos.SangradoUter','examenfisicos.ExpulTapon','examenfisicos.Dilatacioncuello','examenfisicos.Borramiento',
            'examenfisicos.Estacion','examenfisicos.loquios','examenfisicos.Tactorecfem','examenfisicos.TemTono',
            'examenfisicos.checkboxPerine','examenfisicos.DesgarroPerine','examenfisicos.Episiorragia',
            'examenfisicos.checkboxExtremidades','examenfisicos.checkboxSistemaNervioso','examenfisicos.SistemaNervioso',
            'examenfisicos.ParesCraneales','examenfisicos.EvaluacionMarcha','examenfisicos.EvaluacionTonoMuscular',
            'examenfisicos.EvaluacionFuerza','examenfisicos.EvaluacionEsfera','examenfisicos.checkboxPielFaneras',
            'examenfisicos.PielFaneras','examenfisicos.checkboxSistemaOsteo','examenfisicos.SistemaOsteo',
            'examenfisicos.Cuello','examenfisicos.FiO','examenfisicos.PesoGanancia','examenfisicos.Imcbebe',
            'examenfisicos.gananciaesperada','examenfisicos.DorsoFetal','examenfisicos.motricidadGruesa',
            'examenfisicos.motricidadFina','examenfisicos.audicionLenguaje','examenfisicos.personalSocial',
            'examenfisicos.cuidado','examenfisicos.escalaDesarrollo','examenfisicos.autismo',
            'examenfisicos.resultadoVale','examenfisicos.actividades','examenfisicos.comportamientos',
            'examenfisicos.autoeficacia','examenfisicos.independencia','examenfisicos.actividadesProposito',
            'examenfisicos.controlInterno','examenfisicos.funcionesEjecutivas','examenfisicos.Identidad',
            'examenfisicos.valoracionIdentidad','examenfisicos.Autonomia','examenfisicos.valoracionAutonomia',
            'examenfisicos.visualnino','examenfisicos.problemaOido','examenfisicos.escuchaBien',
            'examenfisicos.factoresOido','examenfisicos.riesgosMentalesNinos','examenfisicos.lesionesAutoinflingidas',
            'examenfisicos.alteracionesGenitales','examenfisicos.tannerMasculino','examenfisicos.alteracionesGenitalesExternos',
            'examenfisicos.tannerFemenino','examenfisicos.tannerVello','examenfisicos.autocontrol','examenfisicos.funciones',
            'examenfisicos.desempeñocomunicativo','examenfisicos.violencia_mental','examenfisicos.violencia_conflicto',
            'examenfisicos.violencia_sexual','examenfisicos.rendimiento_escolar','examenfisicos.test_figura_humana',
            'examenfisicos.columna_vertebral','examenfisicos.examen_mental','examenfisicos.circunferencia_brazo',
            'examenfisicos.circunferencia_pantorrilla','examenfisicos.peso_talla','examenfisicos.clasificacion_peso_talla',
            'examenfisicos.talla_edad','examenfisicos.clasificacion_talla_edad','examenfisicos.cefalico_edad',
            'examenfisicos.clasificacion_cefalico_edad','examenfisicos.imc_edad','examenfisicos.clasificacion_imc_edad',
            'examenfisicos.peso_edad','examenfisicos.clasificacion_peso_edad',
            'Pacientes.RH','Pacientes.Orden_Judicial','Pacientes.portabilidad')
            ->join('cita_paciente','cita_paciente.Paciente_id','Pacientes.id')
            ->join('examenfisicos','examenfisicos.Citapaciente_id','cita_paciente.id')
            ->where('Pacientes.Num_Doc', $cedula)
            ->where('cita_paciente.Tipocita_id',8)
            ->orderby('examenfisicos.id', 'DESC')
            ->first();
        return response()->json([
            'paciente' => $paciente,
        ], 200);
    }

    public function consultaPatologias($cedula){
        $paciente = Paciente::select('pacienteantecedentes.Descripcion','antecedentes.Nombre',
            DB::raw("CONCAT(users.name,' ',users.apellido) as NombreCompleto"))
            ->join('cita_paciente','cita_paciente.Paciente_id','Pacientes.id')
            ->join('pacienteantecedentes','pacienteantecedentes.Citapaciente_id','cita_paciente.id')
            ->join('antecedentes','antecedentes.id','pacienteantecedentes.Antecedente_id')
            ->join('users','users.id','pacienteantecedentes.Usuario_id')
            ->where('Pacientes.Num_Doc', $cedula)
            ->where('cita_paciente.Tipocita_id',8)
            ->get();
        return response()->json($paciente, 200);
    }

    public function trascripciones($cedula)
    {
        $paciente = Paciente::where('Num_Doc', $cedula)->first();
        if (!$paciente) {
            return response()->json([
                'message' => 'El paciente no existe en el sistema',
            ], 201);
        } else {
            $paciente = Paciente::select(
                'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
                'pacientes.Segundo_Ape','pacientes.Tipo_Doc','pacientes.ppp','pacientes.abrazarte','pacientes.latir_sentido',
                'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
                'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
                'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
                'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
                'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
                'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
                'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
                'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
                'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
                'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
                'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
                'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
                'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
                'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
                'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
                'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
                'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
                'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
                'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
                'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
                'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
                'sedeproveedores.Nombre as NombreIPS', 'e.nombre as entidad')
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->where('Num_Doc', $cedula)
                ->where('Estado_Afiliado', 1)
                ->whereIn('pacientes.entidad_id', [1,2,3,7])
                ->first();

            if (!isset($paciente)) {
                return response()->json([
                    'message' => 'El paciente consultado no existe en nuestra base de datos validar en HOSVITAL si cambio de UT',
                ]);
                // Estado 2 el paciente tiene derecho a garantia en salud
            } elseif ($paciente->Estado_Afiliado == 2) {
                return response()->json([
                    'message' => 'El paciente Se encuentra retirado de Sumimedical',
                ], 200);
                //Estado 3 el paciente tiene derecho a garantia en salud
            } elseif ($paciente->Estado_Afiliado == 3) {
                return response()->json([
                    'message' => 'El paciente se encuentra en Proteccion Laboral Cotizante',
                ], 200);
            } elseif ($paciente->Estado_Afiliado == 4) {
                return response()->json([
                    'message' => 'El paciente se encuentra en Proteccion Laboral  Beneficiario',
                ], 200);
            } elseif ($paciente->Estado_Afiliado == 27) {
                return response()->json([
                    'message' => 'El paciente Se encuentra retirado de Sumimedical',
                ], 200);
            }
            return response()->json([
                'paciente' => $paciente,
            ], 200);
        }
    }

    public function showEnabledmedimas($cedula)
    {
        $paciente = Paciente::select(
            'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
            'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
            'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
            'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
            'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
            'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
            'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
            'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
            'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
            'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
            'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
            'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
            'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
            'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
            'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
            'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
            'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
            'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
            'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
            'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
            'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
            'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
            'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
            'sedeproveedores.Nombre as NombreIPS')
            ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
            ->where('Num_Doc', $cedula)
            ->whereIn('pacientes.entidad_id', [1,3])
            ->first();

        if (!isset($paciente)) {
            return response()->json([
                'message' => 'El paciente consultado no existe en nuestra base de datos validar en HOSVITAL si cambio de UT',
            ]);
            // Estado 2 el paciente tiene derecho a garantia en salud
        } elseif ($paciente->Estado_Afiliado == 2) {
            return response()->json([
                'message' => 'El paciente Se encuentra retirado de Sumimedical',
            ], 200);
            //Estado 3 el paciente tiene derecho a garantia en salud
        } elseif ($paciente->Estado_Afiliado == 3) {
            return response()->json([
                'message' => 'El paciente se encuentra en Proteccion Laboral Cotizante',
            ], 200);
        } elseif ($paciente->Estado_Afiliado == 4) {
            return response()->json([
                'message' => 'El paciente se encuentra en Proteccion Laboral  Beneficiario',
            ], 200);
        } elseif ($paciente->Estado_Afiliado == 27) {
            return response()->json([
                'message' => 'El paciente Se encuentra retirado de Sumimedical',
            ], 200);
        }
        return response()->json([
            'paciente' => $paciente,
        ], 200);
    }

    public function showEnabledferrocarril($cedula)
    {
        $paciente = Paciente::where("Num_Doc", $cedula)->first();

        if (!$paciente) {
            return response()->json([
                'message' => 'El paciente consultado no registra en el sistema',
            ]);
            // Estado 2 el paciente tiene derecho a garantia en salud
        } elseif ($paciente->Estado_Afiliado == 2) {
            return response()->json([
                'message' => 'El paciente Se encuentra retirado de Sumimedical',
            ], 200);
            //Estado 3 el paciente tiene derecho a garantia en salud
        } elseif ($paciente->Estado_Afiliado == 3) {
            return response()->json([
                'message' => 'El paciente se encuentra en Proteccion Laboral Cotizante',
            ], 200);
        } elseif ($paciente->Estado_Afiliado == 4) {
            return response()->json([
                'message' => 'El paciente se encuentra en Proteccion Laboral  Beneficiario',
            ], 200);
        } elseif ($paciente->Estado_Afiliado == 27) {
            return response()->json([
                'message' => 'El paciente Se encuentra retirado de Sumimedical',
            ], 200);
        } elseif ($paciente->entidad_id != 3) {
            return response()->json([
                'message' => 'El paciente pertenece a una entidad diferente a ferrocarril',
            ], 200);
        }elseif ($paciente->ut_saliente != null) {
            return response()->json([
                'message' => 'El paciente ya tiene un empalme consulta su estado',
            ], 200);
        }
        return response()->json([
            'paciente' => $paciente,
        ], 200);
    }

    public function update(Request $request, Paciente $paciente)
    {
        // $hoy =new DateTime();
        // $años = $hoy->diff(new DateTime($request->Fecha_Naci));
        // $fechanaci = substr($request->Fecha_Naci, 0, 10);
        $paciente->update(($request->except(['id','created_at', 'updated_at']))
    );
        return response()->json([
            'message' => 'Paciente Actualizado con Exito!',
        ], 200);
    }

    public function updateUbicacionData(Request $request, Paciente $paciente)
    {

        if ($request->hasFile('adjunto')) {

            $arrNames = [];
            foreach($request->file('adjunto') as $file){
                $file_name = $file->getClientOriginalName();
                array_push($arrNames,$file_name);
                $path = '../storage/app/public/adjuntosempalme/' . $paciente->id;
                $file->move($path, $file_name);
            }

            $paciente->update([
                'anexo' => json_encode($arrNames),
                'cups' => json_encode($request->cups),
                'cums' => json_encode($request->cums),
                'propios' => json_encode($request->codes),
            ]);
        }

        return response()->json([
            'message' => 'Paciente de empalme actualizado con exito'
        ], 200);

    }

    public function update2(Request $request, Paciente $paciente)
    {
        $paciente->update([
            'Region' => $request->Region,
            'Ut' => $request->Ut,
            'Primer_Nom' => $request->Primer_Nom,
            'SegundoNom' => $request->SegundoNom,
            'Primer_Ape' => $request->Primer_Ape,
            'Segundo_Ape' => $request->Segundo_Ape,
            'Tipo_Doc' => $request->Tipo_Doc,
            'Num_Doc' => $request->Num_Doc,
            'Sexo' => $request->Sexo,
            'Fecha_Afiliado' => $request->Fecha_Afiliado,
            'Fecha_Naci' => $request->Fecha_Naci,
            'Edad_Cumplida' => $request->Edad_Cumplida,
            'Discapacidad' => $request->Discapacidad,
            'Grado_Discapacidad' => $request->Grado_Discapacidad,
            'Tipo_Afiliado' => $request->Tipo_Afiliado,
            'Orden_Judicial' => $request->Orden_Judicial,
            'Num_Folio' => $request->Num_Folio,
            'Fecha_Emision' => $request->Fecha_Emision,
            'Parentesco' => $request->Parentesco,
            'TipoDoc_Cotizante' => $request->TipoDoc_Cotizante,
            'Doc_Cotizante' => $request->Doc_Cotizante,
            'Tipo_Cotizante' => $request->Tipo_Cotizante,
            'Estado_Afiliado' => $request->Estado_Afiliado,
            'Dane_Mpio' => $request->Dane_Mpio,
            'Mpio_Afiliado' => $request->Mpio_Afiliado,
            'Dane_Dpto' => $request->Dane_Dpto,
            'Departamento' => $request->Departamento,
            'Subregion' => $request->Subregion,
            'Dpto_Atencion' => $request->Dpto_Atencion,
            'Mpio_Atencion' => $request->Mpio_Atencion,
            'IPS' => $request->IPS,
            'Sede_Odontologica' => $request->Sede_Odontologica,
            'Medicofamilia' => $request->Medicofamilia,
            'Etnia' => $request->Etnia,
            'Nivel_Sisben' => $request->Nivel_Sisben,
            'Laboraen' => $request->Laboraen,
            'Mpio_Labora' => $request->Mpio_Labora,
            'Direccion_Residencia' => $request->Direccion_Residencia,
            'Mpio_Residencia' => $request->Mpio_Residencia,
            'Telefono' => $request->Telefono,
            'Correo1' => $request->Correo1,
            'Correo2' => $request->Correo2,
            'Estrato' => $request->Estrato,
            'Celular1' => $request->Celular1,
            'Celular2' => $request->Celular2,
            'Sexo_Biologico' => $request->Sexo_Biologico,
            'Tipo_Regimen' => $request->Tipo_Regimen,
            'Num_Hijos' => $request->Num_Hijos,
            'Vivecon' => $request->Vivecon,
            'RH' => $request->RH,
            'Nivelestudio' => $request->Nivelestudio,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
            'Nombreacompanante' => $request->Nombreacompanante,
            'Telefonoacompanante' => $request->Telefonoacompanante,
            'Nombreresponsable' => $request->Nombreresponsable,
            'Telefonoresponsable' => $request->Telefonoresponsable,
            'Aseguradora' => $request->Aseguradora,
            'Tipovinculacion' => $request->Tipovinculacion,
            'Ocupacion' => $request->Ocupacion,
            'nivel' => $request->nivel,
            'entidad_id' => $request->entidad_id,
            'vlr_upc' => $request->vlr_upc,
            'fecha_ini_cont' => $request->fecha_ini_cont,
            'fecha_fin_cont' => $request->fecha_fin_cont,
            'valor_cont_cap' => $request->valor_cont_cap,
            'valot_cont_pyp' => $request->valot_cont_pyp,
            'sem_cot' => $request->sem_cot,
            'tipo_categoria' => $request->tipo_categoria,
            'ut_saliente' => $request->ut_saliente,
            'estado_civil' => $request->estado_civil,
            'dx' => $request->dx,
            'cups' => $request->cups,
            'cums' => $request->cums,
            'propios' => $request->propios,
            'f_solicitud' => $request->f_solicitud,
            'anexo' => $request->anexo,
            'ruta' => $request->ruta,
            'represa' => $request->represa,
            'justifica_represa' => $request->justifica_represa,
            'observacion_contratista' => $request->observacion_contratista,
            'cargo_laboral' => $request->cargo_laboral,
            'composicion_familiar' => $request->composicion_familiar,
            'vivienda' => $request->vivienda,
            'personas_a_cargo' => $request->personas_a_cargo,
            'tipo_contratacion' => $request->tipo_contratacion,
            'antiguedad_en_empresa' => $request->antiguedad_en_empresa,
            'antiguedad_cargo_actual' => $request->antiguedad_cargo_actual,
            'numero_cursos_a_cargo' => $request->numero_cursos_a_cargo,
            'password' => $request->password,
            'portabilidad' => $request->portabilidad,
            'ciclo_vida' => $request->ciclo_vida,
            'regional' => $request->regional,
            'tipo_vivienda' => $request->tipo_vivienda,
            'zona_vivienda' => $request->zona_vivienda,
            'numero_habitaciones' => $request->numero_habitaciones,
            'numero_miembros' => $request->numero_miembros,
            'acceso_vivienda' => $request->acceso_vivienda,
            'seguridad_vivienda' => $request->seguridad_vivienda,
            'hogar_con_agua' => $request->hogar_con_agua,
            'prepara_comida_con' => $request->prepara_comida_con,
            'vivienda_con_energia' => $request->vivienda_con_energia,
            'mascota' => $request->mascota,
            'religion' => $request->religion,
            'nombre_pareja' => $request->nombre_pareja,
            'pareja_actual_padre' => $request->pareja_actual_padre,
            'ocupacion_padre' => $request->ocupacion_padre,
            'grupo_sanguineo_padre' => $request->grupo_sanguineo_padre,
            'municipio_nacimiento' => $request->municipio_nacimiento,
            'pais_nacimiento' => $request->pais_nacimiento,
            'Otradiscapacidad' => $request->Otradiscapacidad,
            'victima_conficto_armado' => $request->victima_conficto_armado,
            'domiciliaria' => $request->domiciliaria,
            'edad_horus' => $request->edad_horus,
            'fecha_nacimiento_horus' => $request->fecha_nacimiento_horus,
            'medicofamilia2' => $request->medicofamilia2,
            // 'Direccion_Residencia' => $request->Direccion_Residencia,
            // 'Etnia' => $request->Etnia,
            // 'Telefono' => $request->Telefono,
            // 'Correo1' => $request->Correo1,
            // 'Correo2' => $request->Correo2,
            // 'Estrato' => $request->Estrato,
            // 'Celular1' => $request->Celular1,
            // 'Celular2' => $request->Celular2,
            // 'Nivelestudio' => $request->nivel_escolaridad,
            // 'estado_civil' => $request->estado_civil,
            // 'ut_saliente' => $request->ut_saliente,
            // 'Num_Hijos' => $request->numero_hijos,
            // 'estrato' => $request->estrato,
            // 'dx' => $request->cie10_id,
            // 'f_solicitud' => $request->fecha_solicitud,
            // 'Tienetutela' => $request->tutela,
            // 'represa' => $request->acepta_represa,
            // 'justifica_represa' => $request->justificacion_no_aceptacion,
            // 'observacion_contratista' => $request->observaciones_contratista,
        ]);

    }

    public function updateEmail(Request $request, Paciente $paciente)
    {
        $paciente->update([
            'Correo1' => $request->Correo,
        ]);
        return response()->json([
            'message' => '¡Correo del paciente actualizado!',
        ], 200);
    }
    public function  eventoPaciente($cedula) {
        $paciente = Paciente::select(
            'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
            'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
            'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
            'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
            'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
            'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
            'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
            'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
            'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
            'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
            'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
            'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
            'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
            'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
            'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
            'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
            'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
            'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
            'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
            'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
            'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
            'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
            'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
            'ent.nombre as entidad')
            ->join('entidades as ent', 'pacientes.entidad_id', 'ent.id')
            ->where("Num_Doc", $cedula)
            ->first();

        if (!isset($paciente)) {
            return response()->json([
                'Message' => 'Paciente no existe en el sistema',
            ], 404);
        }
        return response()->json($paciente, 200);
    }

    public function getPaciente($cedula)
    {
        $paciente = Paciente::select(
            'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
            'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
            'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
            'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
            'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
            'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
            'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia','pacientes.Etnia','pacientes.Nivel_Sisben',
            'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
            'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
            'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
            'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
            'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
            'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
            'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
            'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
            'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
            'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
            'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
            'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
            'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
            'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
            'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
            'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
            'ent.nombre as entidad')
        ->join('entidades as ent', 'pacientes.entidad_id', 'ent.id')
        ->where("Num_Doc", $cedula)
        ->first();

        if (!isset($paciente)) {
            return response()->json([
                'Message' => 'Paciente no existe en el sistema',
            ], 404);
        }
        return response()->json($paciente, 200);
    }

    public function adjuntosPaciente($cedula)
    {
        $paciente = Paciente::select(['Pacientes.anexo', 'Pacientes.id'])
            ->where("Num_Doc", $cedula)
            ->whereNotNull('anexo')
            ->first();
        if (!isset($paciente)) {
            return response()->json([
                'Message' => 'Paciente no existe en el sistema',
            ], 404);
        }
        return response()->json($paciente, 200);
    }

    public function getPacienteWithCita(citapaciente $citapaciente)
    {
        $paciente = Paciente::where("id", $citapaciente->Paciente_id)->first();

        return response()->json($paciente, 200);
    }

    public function  guardarNovedades(Request $request)
    {
        try {
                $fechaHoy = Carbon::now()->format('Y-m-d');
                $novedades = NovedadesPaciente::create([
                    'paciente_id'=>$request['paciente']['id'],
                    'Tipo_id' =>$request['novedad']['tipoId'],
                    'fecha_novedad'=>$request['novedad']['fecha'],
                    'fecha_creacion' => $fechaHoy,
                    'movtivo'=>$request['novedad']['causa'],
                    'user_id' => auth()->id(),
                ]);

                $novedadesid = NovedadesPaciente::orderby('id','DESC')->first();
                $auditoriaNovedades = AuditoriaNovedades::create([
                    'novedad_id'=> $novedadesid->id,
                    'paciente_id'=>$request['paciente']['id'],
                    'Tipo_id' =>$request['novedad']['tipoId'],
                    'fecha_novedad'=>$request['novedad']['fecha'],
                    'fecha_creacion' => $fechaHoy,
                    'movtivo'=>$request['novedad']['causa'],
                    'userActualiza_id' => auth()->id(),
                ]);

                // broadcast(new NotificacionEvent('usuario Actualizado'));
                return response()->json([
                    'res' => true,
                    'message' => 'informacion guardad con exito',
                    'Novedades' => $novedades,
                    'AuditoriaNovedades' => $auditoriaNovedades

                ]);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'mensage' => 'Error al guardar novedad'
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    public function novedadesPacientes($cedula){
        $novedades = NovedadesPaciente::select('novedades_pacientes.id as novedadId','novedades_pacientes.fecha_creacion as fechaCreacion',
            'novedades_pacientes.updated_at as fechaActualizacion','estados.Nombre as estado',
            'novedades_pacientes.fecha_novedad as fechaNovedad','novedades_pacientes.movtivo','tipos.Nombre as tipoNovedad','tipos.id as tipoId',
            DB::raw("CONCAT(users.name,' ',users.apellido) as usuario"))
            ->join('tipos','tipos.id','novedades_pacientes.Tipo_id')
            ->join('users','users.id','novedades_pacientes.user_id')
            ->join('estados','estados.id','novedades_pacientes.estado_id')
            ->join('pacientes','pacientes.id','novedades_pacientes.paciente_id')
            ->where('pacientes.Num_Doc',$cedula)
            ->whereIn('novedades_pacientes.estado_id',[1,16])->get();
        return response()->json($novedades
        );
    }

    public function updateNovedades(Request $request)
    {

        $novedad = $request->novedadId;
        $novedades = NovedadesPaciente::where('id', $novedad)
            ->update([
                'Tipo_id' => $request->tipoId,
                'fecha_novedad' => $request->fechaNovedad,
                'fecha_creacion' => $request->fechaCreacion,
                'movtivo' => $request->movtivo,
                'user_id' => auth()->id(),]);

        $paciente = Paciente::select('pacientes.id')
            ->join('novedades_pacientes', 'novedades_pacientes.paciente_id', 'pacientes.id')
            ->where('novedades_pacientes.id', $novedad)->first();


        $auditoriaNovedades = AuditoriaNovedades::create([
            'novedad_id'=> $novedad,
            'paciente_id'=>$paciente->id,
            'Tipo_id' =>$request->tipoId,
            'fecha_novedad'=>$request->fechaNovedad,
            'fecha_creacion' => $request->fechaCreacion,
            'movtivo'=>$request->movtivo,
            'userActualiza_id' => auth()->id(),]);

        return response()->json([
            'message' => 'informacion guardad con exito',
            'Novedades' => $novedades,
            'AuditoriaNovedades' => $auditoriaNovedades

        ]);

    }

    public function guardarAdjuntos($novedadId,Request $request){

        if ($files = $request->file('files')) {
            foreach ($files as $file) {
                $filename      = $file->getClientOriginalName();
                $path = '../storage/app/public/adjuntosNovedades/'.$novedadId;
                $paths = '/storage/adjuntosNovedades/'.$novedadId.'/'.time().$filename;
                if ($file->move($path, time().$filename)) {
                    Adjunto_novedades::create([
                        'novedad_id' => $novedadId,
                        'nombre'    => $filename,
                        'ruta'      => $paths
                    ]);
                }
            }
        }

    }

    public function adjuntosNotificaciones($novedadId){
        $adjuntos = Adjunto_novedades::where('novedad_id',$novedadId)->get();
        return response()->json($adjuntos, 200);

    }

    public function generarInforme(Request $request){
        $result = [];
        switch ($request->informe) {
            case 1:
                $users = auth()->user()->prestador_id;
                $pacientes = (DB::select('exec dbo.SP_ReportesPacientes ?,?,?', [$request->entidad,$users,1]));
                if(!count($pacientes)){
                    return false;
                }
                if($request->entidad == 1){
                    $result = collect($pacientes);
                }else{
                    $result = json_decode(collect($pacientes), true);
                }
                break;
            case 2:
                // $demandas = DemandaInsatisfecha::select(
                // 'demanda_insatisfechas.created_at as "Fecha de registro"',
                // 'especialidades.Nombre as Especialidad',
                // 'tipo_agendas.name as Actividad',
                // 'demanda_insatisfechas.observacion',
                // 'estados.Nombre as Estado',
                // DB::raw("CONCAT(us.name,' ',us.apellido) as 'Usuario Crea'"),
                // DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as Paciente"),
                // 'pacientes.Num_Doc as Documento',
                // 'Pacientes.Region',
                // 'Pacientes.Ut',
                // 'sedeproveedores.Nombre as "IPS basica"',
                // 'municipios.Nombre as Municipio IPS',
                // DB::raw("case when cita_asignada_id is null then 'Sin cita Asignada' else 'Cita Asiganada' end 'Cita Asignada'"),
                // DB::raw("CONCAT(u.name,' ',u.apellido) as 'Usuario Asgina la cita'"),
                // 'consultorios.Nombre as Consultorio',
                // 'sedes.Nombre as Sede',
                // 'citas.Hora_Inicio as "Inicio Cita"',
                // 'citas.Hora_Final as "Fin Cita"',
                // 'us.especialidad_medico as cargo')
                // ->leftjoin('citas','citas.id','demanda_insatisfechas.cita_asignada_id')
                // ->leftjoin('users as us','demanda_insatisfechas.user_id','us.id')
                // ->leftjoin('cita_paciente','cita_paciente.Cita_id','citas.id')
                // ->leftjoin('agendas','agendas.id','citas.Agenda_id')
                // ->leftjoin('consultorios','consultorios.id','agendas.Consultorio_id')
                // ->leftjoin('sedes','sedes.id','consultorios.Sede_id')
                // ->leftjoin('users as u','cita_paciente.Usuario_id','u.id')
                // ->join('especialidades','especialidades.id','demanda_insatisfechas.especialidad_id')
                // ->join('pacientes','pacientes.id','demanda_insatisfechas.paciente_id')
                // ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                // ->leftjoin('municipios','municipios.id','sedeproveedores.Municipio_id')
                // ->join('tipo_agendas','tipo_agendas.id','demanda_insatisfechas.tipo_agenda_id')
                // ->join('estados','estados.id','demanda_insatisfechas.estado_id')
                // ->whereBetween('demanda_insatisfechas.created_at', [$request->fechaInicio.' 00:00:00.000', $request->fechaFinal.' 23:59:00.000'])
                // ->distinct()
                // ->get();

                $demanda = (DB::select('SET NOCOUNT ON exec dbo.SP_DemandaInsatisfecha ?,?', [$request->fechaInicio.' 00:00:00.000', $request->fechaFinal.' 23:59:00.000']));
                // $result = collect($demanda);
                $result = json_decode(collect($demanda), true);
                break;
            case 3:
                $Barrera = barreraAcceso::select(
                'barrera_accesos.created_at as "Fecha de registro"',
                'barrera_accesos.barrera',
                'barrera_accesos.observacion',
                'estados.Nombre as Estado',
                DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as Paciente"),
                'pacientes.Num_Doc as Documento',
                'Pacientes.Region',
                'Pacientes.Ut',
                'sedeproveedores.Nombre as "IPS basica"',
                DB::raw("CONCAT(users.name,' ',users.apellido) as 'usuario ingresa'"),
                'estados.nombre as estado',
                'barrera_accesos.observacion_cierre')
                ->join('users','users.id','barrera_accesos.user_id')
                ->join('pacientes','pacientes.id','barrera_accesos.paciente_id')
                ->join('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->join('estados','estados.id','barrera_accesos.estado_id')
                ->whereBetween('barrera_accesos.created_at', [$request->fechaInicio.' 00:00:00.000', $request->fechaFinal.' 23:59:00.000'])
                ->get();
                $result = collect($Barrera);
                break;
        }
        return (new FastExcel($result))->download('file.xls');

    }
    public function buscarPaciente($cedula){
        $paciente = Paciente::select('pacientes.id as pacienteid','pacientes.*', 'ent.id as entidad','estados.id as estadoPaciente','estados.nombre as estadoNombre',
            'municipios.Nombre as municipioAtencion','departamentos.Nombre as departamentoAtencion','sedeproveedores.id as sedeAtencion',
            DB::raw("CONCAT(us.name,' ',us.apellido) as Medico"),
            'sedeproveedores.nombre as sedeAtencionNombre')
            ->join('entidades as ent', 'pacientes.entidad_id', 'ent.id')
            ->join('sedeproveedores','sedeproveedores.id','pacientes.IPS')
            ->join('estados','estados.id','pacientes.Estado_Afiliado')
            ->leftjoin('municipios','municipios.id','pacientes.Mpio_Atencion')
            ->leftjoin('departamentos','departamentos.id','pacientes.Dpto_Atencion')
            ->leftjoin('users as us','pacientes.Medicofamilia','us.id')
            ->where("Num_Doc", $cedula)
            ->get();

        if (!isset($paciente)) {
            return response()->json([
                'Message' => 'Paciente no existe en el sistema',
            ], 404);

        }

        $Novedades = Tipo::where("Descripcion","Novedades")
            ->where("Estado_id",1)->get();
        $entidades = Entidade::select('id as entidad','nombre')->get();
        $sedeprestador = Sedeproveedore::select('id as sedeAtencion','Nombre')->get();
        $estados = Estado::select('id as estadoId','Nombre')
            ->whereIn('id',[1,27,28,29])->get();

        return response()->json([
            'Pacientes'=>$paciente,
            'Novedades' =>$Novedades,
            'Entidades' =>$entidades,
            'Sedes' =>$sedeprestador,
            'Estados'=> $estados
        ], 200);
    }

    public function pacienteEnabled($paciente_id)
    {
        $paciente = Paciente::where('id', $paciente_id)->first();
        if ($paciente->Estado_Afiliado == 1 || $paciente->Estado_Afiliado == 27) {
            $paciente = Paciente::select(
                'Pacientes.id','Pacientes.Region','Pacientes.Ut','Pacientes.Primer_Nom','Pacientes.Otradiscapacidad',
                'Pacientes.SegundoNom','Pacientes.Primer_Ape','Pacientes.Segundo_Ape',
                'Pacientes.Tipo_Doc','Pacientes.Num_Doc','Pacientes.Sexo','Pacientes.Fecha_Afiliado',
                'Pacientes.Fecha_Naci','Pacientes.Edad_Cumplida','Pacientes.Discapacidad',
                'Pacientes.Grado_Discapacidad','Pacientes.Tipo_Afiliado','Pacientes.Orden_Judicial',
                'Pacientes.Num_Folio','Pacientes.Fecha_Emision','Pacientes.Parentesco','Pacientes.TipoDoc_Cotizante',
                'Pacientes.Doc_Cotizante','Pacientes.Tipo_Cotizante','Pacientes.Estado_Afiliado','Pacientes.Dane_Mpio',
                'Pacientes.Mpio_Afiliado','Pacientes.Dane_Dpto','Pacientes.Departamento','Pacientes.Subregion',
                'Pacientes.Dpto_Atencion','Pacientes.Mpio_Atencion','Pacientes.IPS','Pacientes.Sede_Odontologica',
                'Pacientes.Medicofamilia','Pacientes.Etnia','Pacientes.Nivel_Sisben','Pacientes.Laboraen',
                'Pacientes.Mpio_Labora','Pacientes.Direccion_Residencia','Pacientes.Mpio_Residencia',
                'Pacientes.Telefono','Pacientes.Correo1','Pacientes.Correo2','Pacientes.Estrato',
                'Pacientes.Celular1','Pacientes.Celular2','Pacientes.Sexo_Biologico','Pacientes.Tipo_Regimen',
                'Pacientes.Num_Hijos','Pacientes.Vivecon','Pacientes.RH','Pacientes.Tienetutela',
                'Pacientes.Nivelestudio','Pacientes.created_at','Pacientes.updated_at','Pacientes.Nombreacompanante',
                'Pacientes.Telefonoacompanante','Pacientes.Nombreresponsable','Pacientes.Telefonoresponsable',
                'Pacientes.Aseguradora','Pacientes.Tipovinculacion','Pacientes.Ocupacion','Pacientes.nivel',
                'Pacientes.entidad_id','Pacientes.vlr_upc','Pacientes.fecha_ini_cont','Pacientes.fecha_fin_cont',
                'Pacientes.valor_cont_cap','Pacientes.valot_cont_pyp','Pacientes.sem_cot','Pacientes.tipo_categoria',
                'Pacientes.ut_saliente','Pacientes.estado_civil','Pacientes.dx','Pacientes.cups','Pacientes.cums',
                'Pacientes.propios','Pacientes.f_solicitud','Pacientes.anexo','Pacientes.ruta','Pacientes.represa',
                'Pacientes.justifica_represa','Pacientes.observacion_contratista','Pacientes.cargo_laboral',
                'Pacientes.composicion_familiar','Pacientes.vivienda','Pacientes.personas_a_cargo','Pacientes.ppp','Pacientes.abrazarte','Pacientes.latir_sentido',
                'Pacientes.tipo_contratacion','Pacientes.antiguedad_en_empresa','Pacientes.antiguedad_cargo_actual',
                'Pacientes.numero_cursos_a_cargo','Pacientes.password','Pacientes.portabilidad','Pacientes.ciclo_vida',
                'Pacientes.regional','Pacientes.tipo_vivienda','Pacientes.zona_vivienda','Pacientes.numero_habitaciones',
                'Pacientes.numero_miembros','Pacientes.acceso_vivienda','Pacientes.seguridad_vivienda','Pacientes.hogar_con_agua',
                'Pacientes.prepara_comida_con','Pacientes.vivienda_con_energia','Pacientes.mascota','Pacientes.religion',
                'Pacientes.nombre_pareja','Pacientes.pareja_actual_padre','Pacientes.ocupacion_padre','Pacientes.grupo_sanguineo_padre',
                'Pacientes.municipio_nacimiento','Pacientes.pais_nacimiento','sedeproveedores.Nombre as NombreIPS', 'e.nombre as entidad',
                 DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as NombreCompleto"),
                DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"))
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->leftjoin('users','users.id','pacientes.Medicofamilia')
                ->where('Pacientes.id', $paciente_id)
                ->first();
                return response()->json($paciente, 200);
        }else {
            $paciente = Paciente::select(
                'Pacientes.id','Pacientes.Region','Pacientes.Ut','Pacientes.Primer_Nom','Pacientes.Otradiscapacidad',
                'Pacientes.SegundoNom','Pacientes.Primer_Ape','Pacientes.Segundo_Ape',
                'Pacientes.Tipo_Doc','Pacientes.Num_Doc','Pacientes.Sexo','Pacientes.Fecha_Afiliado',
                'Pacientes.Fecha_Naci','Pacientes.Edad_Cumplida','Pacientes.Discapacidad',
                'Pacientes.Grado_Discapacidad','Pacientes.Tipo_Afiliado','Pacientes.Orden_Judicial',
                'Pacientes.Num_Folio','Pacientes.Fecha_Emision','Pacientes.Parentesco','Pacientes.TipoDoc_Cotizante',
                'Pacientes.Doc_Cotizante','Pacientes.Tipo_Cotizante','Pacientes.Estado_Afiliado','Pacientes.Dane_Mpio',
                'Pacientes.Mpio_Afiliado','Pacientes.Dane_Dpto','Pacientes.Departamento','Pacientes.Subregion',
                'Pacientes.Dpto_Atencion','Pacientes.Mpio_Atencion','Pacientes.IPS','Pacientes.Sede_Odontologica',
                'Pacientes.Medicofamilia','Pacientes.Etnia','Pacientes.Nivel_Sisben','Pacientes.Laboraen',
                'Pacientes.Mpio_Labora','Pacientes.Direccion_Residencia','Pacientes.Mpio_Residencia',
                'Pacientes.Telefono','Pacientes.Correo1','Pacientes.Correo2','Pacientes.Estrato',
                'Pacientes.Celular1','Pacientes.Celular2','Pacientes.Sexo_Biologico','Pacientes.Tipo_Regimen',
                'Pacientes.Num_Hijos','Pacientes.Vivecon','Pacientes.RH','Pacientes.Tienetutela',
                'Pacientes.Nivelestudio','Pacientes.created_at','Pacientes.updated_at','Pacientes.Nombreacompanante',
                'Pacientes.Telefonoacompanante','Pacientes.Nombreresponsable','Pacientes.Telefonoresponsable',
                'Pacientes.Aseguradora','Pacientes.Tipovinculacion','Pacientes.Ocupacion','Pacientes.nivel',
                'Pacientes.entidad_id','Pacientes.vlr_upc','Pacientes.fecha_ini_cont','Pacientes.fecha_fin_cont',
                'Pacientes.valor_cont_cap','Pacientes.valot_cont_pyp','Pacientes.sem_cot','Pacientes.tipo_categoria',
                'Pacientes.ut_saliente','Pacientes.estado_civil','Pacientes.dx','Pacientes.cups','Pacientes.cums',
                'Pacientes.propios','Pacientes.f_solicitud','Pacientes.anexo','Pacientes.ruta','Pacientes.represa',
                'Pacientes.justifica_represa','Pacientes.observacion_contratista','Pacientes.cargo_laboral',
                'Pacientes.composicion_familiar','Pacientes.vivienda','Pacientes.personas_a_cargo',
                'Pacientes.tipo_contratacion','Pacientes.antiguedad_en_empresa','Pacientes.antiguedad_cargo_actual',
                'Pacientes.numero_cursos_a_cargo','Pacientes.password','Pacientes.portabilidad','Pacientes.ciclo_vida',
                'Pacientes.regional','Pacientes.tipo_vivienda','Pacientes.zona_vivienda','Pacientes.numero_habitaciones',
                'Pacientes.numero_miembros','Pacientes.acceso_vivienda','Pacientes.seguridad_vivienda','Pacientes.hogar_con_agua',
                'Pacientes.prepara_comida_con','Pacientes.vivienda_con_energia','Pacientes.mascota','Pacientes.religion',
                'Pacientes.nombre_pareja','Pacientes.pareja_actual_padre','Pacientes.ocupacion_padre','Pacientes.grupo_sanguineo_padre',
                'Pacientes.municipio_nacimiento','Pacientes.pais_nacimiento','sedeproveedores.Nombre as NombreIPS', 'e.nombre as entidad',
                 DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as NombreCompleto"),
                DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"))
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->leftjoin('users','users.id','pacientes.Medicofamilia')
                ->where('Pacientes.id', $paciente_id)
                ->where('Pacientes.Estado_Afiliado', 1)
                ->first();
                if ($paciente) {
                    return response()->json($paciente, 200);
                }else{
                    return response()->json([
                        'message' => 'El paciente no esta activo',
                    ], 200);
                }
        }


    }

    public function updatePaciente(Request $request)
    {
        $paciente = Paciente::where('Num_Doc', $request['Num_Doc'])->first();
        try {
            $paciente->update($request->except(['NombreIPS', 'entidad', 'NombreCompleto', 'Medico']));
            return response()->json([
                'message' => 'Paciente Actualizado con exito'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 400);
        }

    }
    public function historialCliente(Request $request)
    {
        $citapaciente = citapaciente::select('cita_paciente.id as cita_paciente_id',
        'cita_paciente.Tipocita_id','cita_paciente.Datetimeingreso',
        'f.Destinopaciente',
        DB::raw("CONCAT(u.name,' ',u.apellido) as Atendido_Por"),
        DB::raw("CONCAT(b.Primer_Nom, ' ', b.[SegundoNom], ' ', b.[Primer_Ape], ' ', b.[Segundo_Ape]) AS Nombre"),
        'u.especialidad_medico as Especialidad','t.Nombre as Tipocita'
        )
        ->join('users as u','cita_paciente.user_medico_atiende','u.id')
        ->JOIN('pacientes as b','cita_paciente.Paciente_id','b.id')
        ->join('tipocitas as t','cita_paciente.Tipocita_id','t.id')
        ->join('conductas as f','cita_paciente.id','f.Citapaciente_id')
        ->where('cita_paciente.Paciente_id',$request['paciente_id'])
        ->where('cita_paciente.Estado_id', 9)
        ->orderBy('cita_paciente.datetimeingreso', 'desc')
        ->get();
        return response()->json($citapaciente, 200);
    }
    public function historialFarmacologico(Request $request){
        $medicamentos = Detaarticulorden::select(
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
            'detaarticulordens.Cantidadmensualdisponible as Cantidad_Mensual_Disponible',
            'detaarticulordens.Orden_id as Orden_id',
            'detaarticulordens.Fechaorden as Fecha_Orden',
            'detaarticulordens.Estado_id as Estado_id',
            'detaarticulordens.mipres',
            'au.Nota as nota_autorizacion',
            'au.created_at as fechaAutorizacion',
            'e.Nombre as Estado',
            'c.Nombre as Nombre',
            'c.Codigo as Codigo',
            'c.id as id_codesumi',
            'c.Requiere_autorizacion as Requiere_Autorizacion',
            'o.id as or',
            'o.paginacion',
            'o.Fechaorden'
        )
            ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
            ->join('estados as e','e.id','detaarticulordens.Estado_id')
            ->join('ordens as o','detaarticulordens.Orden_id','o.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->leftjoin('tipocitas as tp','tp.id','cp.Tipocita_id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
            ->where('cp.Paciente_id',$request['paciente_id'])
            ->whereIn('e.id',[1,7,18])
            ->orderBy('o.id','desc')
            ->get();

        return response()->json($medicamentos);
    }
    public function historialExamenes(Request $request){
        $fecha6MesesAtras = date("Y-m-d",strtotime(date('Y-m-d')."- 180 days"));
        $fechahoy = date("Y-m-d",strtotime(date('Y-m-d')."+ 1 days"));
        $servicios = Cuporden::select(
            'cupordens.id',
            'cupordens.created_at as FechaOrdenamiento',
            'cupordens.Cantidad',
            'cupordens.anexo3',
            'e.Nombre as Estado',
            'cupordens.Estado_id',
            'c.Codigo',
            'c.Nombre',
            'c.Requiere_auditoria as autorizacion',
            'o.id as orden',
            'o.Fechaorden',
            'cupordens.cancelacion',
            'cupordens.fecha_solicitada',
            'cupordens.fecha_sugerida',
            'cupordens.cancelacion',
            'cupordens.observaciones',
            'cupordens.responsable',
            'cupordens.motivo',
            'cupordens.causa',
            'cupordens.cirujano',
            'cupordens.especialidad',
            'cupordens.fecha_Preanestesia',
            'cupordens.fecha_cirugia',
            'cupordens.fecha_ejecucion',
            'cupordens.fecha_resultado',
            's.Nombre as Sede_Nombre'
        )
            ->join('cups as c', 'cupordens.Cup_id' , 'c.id')
            ->join('ordens as o','cupordens.Orden_id', 'o.id')
            ->join('estados as e' , 'cupordens.Estado_id','e.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->leftjoin('tipocitas as t' , 'cp.Tipocita_id' , 't.id')
            ->leftjoin('users as u' , 'cp.Usuario_id' , 'u.id')
            ->leftjoin('autorizacions as a' , 'cupordens.id', 'a.Cuporden_id')
            ->leftJoin('sedeproveedores as s', 'cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->where('cp.Paciente_id',$request['paciente_id'])
            ->whereIn('o.Tiporden_id',[2,4])
            ->whereIn('e.id',[1,7])
            ->whereBetween('o.created_at',[$fecha6MesesAtras,$fechahoy])
            ->orderBy('o.Fechaorden','desc')
            ->get();


        return response()->json($servicios);
    }
    public function historialIncapacidades(Request $request){
        $fechahoy = Carbon::now()->format('Y-m-d');
        $incapacidad = Incapacidade::select('incapacidades.id',
        DB::raw("CONCAT(p.Primer_Nom, '  ', p.SegundoNom, '  ', p.Primer_Ape, '  ', p.Segundo_Ape) as Nombre"),
        'p.Num_Doc as Cedula','incapacidades.Orden_id as Orden_id','incapacidades.created_at as Fechacreaincapacidad','incapacidades.Fechainicio',
        'incapacidades.Dias','incapacidades.Fechafinal','incapacidades.created_at as fechaGenerada','incapacidades.Prorroga','incapacidades.Descripcion',
        'incapacidades.Laboraen','incapacidades.Contingencia','incapacidades.Estado_id','sp.Nombre as Proveedor,','p.Correo1 as Correo',
        'p.Celular1 as Celular','p.Sexo','p.Edad_Cumplida','p.Fecha_Naci','p.Tipo_Afiliado as afilia','p.Ut','p.Telefono as tel','spips.Nombre as ips1',
        'p.Mpio_Afiliado AS ma','p.Tipo_Doc as TTipod','cp.id as citaPaciente_id','cp.Tipocita_id as hj',
        'cp.Medicoordeno',DB::raw("CONCAT(d.Codigo_CIE10, ' - ', d.Descripcion) as Diagnostico"),'f.Firma',
        'f.Registromedico as Rmedico','f.especialidad_medico','e.Nombre as Estado')
        ->join('ordens as o','incapacidades.Orden_id', 'o.id')
        ->join('cita_paciente as cp','o.Cita_id','cp.id')
        ->join('pacientes as p','cp.Paciente_id','p.id')
        ->leftjoin('cie10s as d','incapacidades.Cie10_id','d.id')
        ->leftjoin('sedeproveedores as sp','cp.Entidademite',DB::raw("cast(sp.id as nvarchar)"))
	    ->leftjoin('sedeproveedores as spips','p.IPS','spips.id')
	    ->leftjoin('auditorias','auditorias.Model_id','incapacidades.id')
        ->leftjoin('users as f','cp.Usuario_id','f.id')
        ->join('estados as e' , 'incapacidades.Estado_id','e.id')
        ->where('cp.Paciente_id',$request['paciente_id'])
        ->whereIn('e.id',[1])
        ->where('incapacidades.Fechafinal','>',$fechahoy)
        ->get();

        return response()->json($incapacidad);

    }

    public function conteoHistoria(request $request){

        $citapaciente = citapaciente::select(
            DB::raw("COUNT(cita_paciente.id) as count"),
        )
        ->join('users as u','cita_paciente.user_medico_atiende','u.id')
        ->JOIN('pacientes as b','cita_paciente.Paciente_id','b.id')
        ->join('tipocitas as t','cita_paciente.Tipocita_id','t.id')
        ->join('conductas as f','cita_paciente.id','f.Citapaciente_id')
        ->where('cita_paciente.Paciente_id',$request['paciente_id'])
        ->where('cita_paciente.Estado_id', 9)
        ->first();
        return response()->json($citapaciente);

    }

    public function conteoMedicamentos(request $request) {
        $medicamentosCount = Detaarticulorden::select(
            DB::raw("COUNT(o.id) as count"),
        )
            ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
            ->join('estados as e','e.id','detaarticulordens.Estado_id')
            ->join('ordens as o','detaarticulordens.Orden_id','o.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->leftjoin('tipocitas as tp','tp.id','cp.Tipocita_id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
            ->where('cp.Paciente_id',$request['paciente_id'])
            ->whereIn('e.id',[1,7,18])
            ->first();
            return response()->json($medicamentosCount);
    }
    public function conteoServicios(request $request) {
        $fecha6MesesAtras = date("Y-m-d",strtotime(date('Y-m-d')."- 180 days"));
        $fechahoy = date("Y-m-d",strtotime(date('Y-m-d')."+ 1 days"));
        $servicios = Cuporden::select(
            DB::raw("COUNT(o.id) as count"),
        )
            ->join('cups as c', 'cupordens.Cup_id' , 'c.id')
            ->join('ordens as o','cupordens.Orden_id', 'o.id')
            ->join('estados as e' , 'cupordens.Estado_id','e.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->leftjoin('tipocitas as t' , 'cp.Tipocita_id' , 't.id')
            ->leftjoin('users as u' , 'cp.Usuario_id' , 'u.id')
            ->leftjoin('autorizacions as a' , 'cupordens.id', 'a.Cuporden_id')
            ->leftJoin('sedeproveedores as s', 'cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->where('cp.Paciente_id',$request['paciente_id'])
            ->whereIn('o.Tiporden_id',[2,4])
            ->whereIn('e.id',[1,7])
            ->whereBetween('o.created_at',[$fecha6MesesAtras,$fechahoy])
            ->first();


        return response()->json($servicios);
    }
    public function conteoIncapacidades(request $request) {
        $fechahoy = Carbon::now()->format('Y-m-d');
        $incapacidad = Incapacidade::select(DB::raw("COUNT(o.id) as count"))
        ->join('ordens as o','incapacidades.Orden_id', 'o.id')
        ->join('cita_paciente as cp','o.Cita_id','cp.id')
        ->join('pacientes as p','cp.Paciente_id','p.id')
        ->leftjoin('cie10s as d','incapacidades.Cie10_id','d.id')
        ->leftjoin('sedeproveedores as sp','cp.Entidademite',DB::raw("cast(sp.id as nvarchar)"))
	    ->leftjoin('sedeproveedores as spips','p.IPS','spips.id')
	    ->leftjoin('auditorias','auditorias.Model_id','incapacidades.id')
        ->leftjoin('users as f','cp.Usuario_id','f.id')
        ->join('estados as e' , 'incapacidades.Estado_id','e.id')
        ->where('cp.Paciente_id',$request['paciente_id'])
        ->whereIn('e.id',[1])
        ->where('incapacidades.Fechafinal','>',$fechahoy)
        ->first();

        return response()->json($incapacidad);
    }
    public function alertas(request $request){
        $fechahoy = Carbon::now()->format('Y-m-d');
        if($request->alertas == 'Presion Arterial'){
            $PacientesPresion = Paciente::select(
                'cp.id as citapaciente_id',
                'pacientes.Tipo_Doc',
                'pacientes.Dpto_Atencion',
                'pacientes.Num_Doc as Cedula',
                'pacientes.Mpio_Atencion',
                'Pacientes.IPS',
                'pacientes.Sexo',
                'e.Presiondiastolica',
                'e.Presionsistolica',
                'cp.id',
                'e.Presiondiastolica',
                'e.Presionsistolica',
                DB::raw("DATEDIFF(DAY, e.created_at, getdate()) as Dias"),
                'pacientes.Ut',
                DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ', pacientes.Primer_Ape,' ', pacientes.Segundo_Ape) as Nombre"))
            ->join('cita_paciente AS cp','pacientes.id','cp.Paciente_id')
            ->join('examenfisicos AS e','e.Citapaciente_id','cp.id')
                ->where('cp.id', DB::raw("(select top 1 id from cita_paciente where Paciente_id = pacientes.id order by id desc)"))
                ->where('pacientes.Edad_Cumplida','>','17')
                ->where('pacientes.Estado_Afiliado',1)
                ->where('pacientes.Estado_Afiliado',1)
                ->where('cp.Estado_id',9)
                ->where(function($query){
                    $query  ->where('e.Presiondiastolica','<=',40)
                            ->orWhere('e.Presionsistolica','>=', 150)
                            ->orWhere('e.Presiondiastolica','<=', 30)
                            ->orWhere('e.Presiondiastolica','>=', 90);
                })
                ->whereBetween('e.created_at',['2022-05-02 00:00:00.000',$fechahoy.' 23:59:59.999'])
                ->orderby('pacientes.Num_Doc','desc');

                if(isset($request->documento)){
                    $PacientesPresion->where('pacientes.Num_Doc',$request->documento);
                }
                if(isset($request->departamento)){
                    $PacientesPresion->where('pacientes.Dpto_Atencion',$request->departamento);
                }
                if(isset($request->municipio)){
                    $PacientesPresion->where('pacientes.Mpio_Atencion',$request->municipio);
                }
                if(isset($request->ips)){
                    $PacientesPresion->where('pacientes.IPS',$request->IPS);
                }
            return response()->json($PacientesPresion->get()->toArray(), 200);
        }
    }
    public function searchPaciente(request $request){
        $validate = Validator::make($request->all(), [
            'cedula_paciente'    => 'required|min:1|max:15',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
            $paciente = Paciente::select('Pacientes.id as paciente_id','Pacientes.Sexo','pacientes.Edad_Cumplida','pacientes.victima_conficto_armado',
            DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ', pacientes.Primer_Ape,' ', pacientes.Segundo_Ape) as Nombre"))
            ->where('Num_Doc', $request->cedula_paciente)
            ->where('Estado_Afiliado', 1)
            ->whereIn('entidad_id', [1, 3, 7])
            ->first();
            if ($paciente) {
            return response()->json($paciente, 200);
            }else{
            return response()->json([
                'message' => 'El paciente no esta activo',
            ], 200);
            }

        }
        public function fetchEstados(){
            $estados = Estado::select('id','Nombre')
            ->whereIn('id',[1,27,28,29])->get();

            return response()->json($estados, 200);
        }
        public function fetchEntidad(){
            $entidades = Entidade::select('id as entidad','nombre')->get();
            return response()->json($entidades, 200);
        }
        public function guardarPacientePortabilidad(Request $request)
        {
            $mensaje = 'El paciente ya existe!';
            $estado = true;
            $hoy =new DateTime();
            $paciente = Paciente::where('Num_Doc', $request->Num_Doc)->first();
        if (!$paciente) {
            if($request->Fecha_Naci != null || $request->Fecha_Naci != ''){
                $hoy =new DateTime();
                $calculoanios = $hoy->diff(new DateTime($request->Fecha_Naci));
                $anios = $calculoanios->y;
                $fechanaci = date("d/m/Y", strtotime($request->Fecha_Naci));
            }else{
                $fechanaci = null;
                $anios = null;
            }
            $fechaAfiliado = date("d/m/Y", strtotime($request->Fecha_Naci));
            Paciente::create([
                'Region' => $request->Region,
                'Ut' => $request->Ut,
                'Primer_Nom' => strtoupper($request->Primer_Nom),
                'SegundoNom' => ($request->SegundoNom == '' ? null : strtoupper($request->SegundoNom)),
                'Primer_Ape' => strtoupper($request->Primer_Ape),
                'Segundo_Ape' => ($request->Segundo_Ape == '' ? null : strtoupper($request->Segundo_Ape)),
                'Tipo_Doc' => $request->Tipo_Doc,
                'Num_Doc' => $request->Num_Doc,
                'Sexo' => $request->Sexo,
                'Fecha_Afiliado' => $fechaAfiliado,
                'Fecha_Naci' => $fechanaci,
                'Edad_Cumplida' => $anios,
                'Discapacidad' => $request->Discapacidad,
                'Grado_Discapacidad' => $request->Grado_Discapacidad,
                'Tipo_Afiliado' => $request->Tipo_Afiliado,
                'Parentesco' => $request->Parentesco,
                'TipoDoc_Cotizante' => $request->TipoDoc_Cotizante,
                'Doc_Cotizante' => $request->Doc_Cotizante,
                'Tipo_Cotizante' => $request->Tipo_Cotizante,
                'Estado_Afiliado' => $request->estadoPaciente,
                'Dane_Mpio' => $request->Dane_Mpio,
                'Mpio_Afiliado' => $request->Mpio_Afiliado,
                'Dane_Dpto' => $request->Dane_Dpto,
                'Departamento' => $request->Departamento,
                'Subregion' => $request->Subregion,
                'Dpto_Atencion' => $request->departamentoAtencion,
                'Mpio_Atencion' => $request->municipioAtencion,
                'IPS' => $request->sedeAtencion,
                'Sede_Odontologica' => $request->Sede_Odontologica,
                'Medicofamilia' => $request->Medicofamilia,
                'Etnia' => $request->Etnia,
                'Direccion_Residencia' => $request->Direccion_Residencia,
                'Mpio_Residencia' => $request->Mpio_Residencia,
                'Telefono' => $request->Telefono,
                'Correo1' => $request->Correo1,
                'Correo2' => $request->Correo2,
                'Celular1' => $request->Celular1,
                'Celular2' => $request->Celular2,
                'entidad_id' => $request->entidad_id,
                'portabilidad' => $request->portabilidad
            ]);
                $paciente = Paciente::where('Num_Doc', $request->Num_Doc)->first();
                NovedadesPaciente::create([
                    'paciente_id' => $paciente->id,
                    'Tipo_id' => 36,
                    'fecha_novedad' => $hoy,
                    'fecha_creacion' => $hoy,
                    'movtivo' => 'Nuevo Paciente en Portabilidad',
                    'user_id' => auth()->id(),
                    'estado_id' => 1,
                    'ipsorigen_portabilidad' => $request->ipsorigen_portabilidad,
                    'telefonoorigen_portabilidad' => $request->telefonoorigen_portabilidad,
                    'correoorigen_portabilidad' => $request->correoorigen_portabilidad,
                    'fechaInicio_portabilidad' => $request->fechaInicio_portabilidad,
                    'fechaFinal_portabilidad' => $request->fechaFinal_portabilidad,
            ]);
                $mensaje = 'Paciente en Portabilidad creado con Exito!';
                $estado = false;
                return response()->json(['mensaje' => $mensaje, 'estado' => $estado]);
            }
            else{
                if($request->Fecha_Naci != null || $request->Fecha_Naci != ''){
                    $hoy =new DateTime();
                    $calculoanios = $hoy->diff(new DateTime($request->Fecha_Naci));
                    $anios = $calculoanios->y;
                    $fechanaci = date("d/m/Y", strtotime($request->Fecha_Naci));
                }else{
                    $fechanaci = null;
                    $anios = null;
                }
                $paciente->update([
                    'Region' => $request->Region,
                    'Ut' => $request->Ut,
                    'Primer_Nom' => strtoupper($request->Primer_Nom),
                    'SegundoNom' => ($request->SegundoNom == '' ? null : strtoupper($request->SegundoNom)),
                    'Primer_Ape' => strtoupper($request->Primer_Ape),
                    'Segundo_Ape' => ($request->Segundo_Ape == '' ? null : strtoupper($request->Segundo_Ape)),
                    'Tipo_Doc' => $request->Tipo_Doc,
                    'Num_Doc' => $request->Num_Doc,
                    'Sexo' => $request->Sexo,
                    'Fecha_Afiliado' => $request->Fecha_Afiliado,
                    'Fecha_Naci' => $fechanaci,
                    'Edad_Cumplida' => $anios,
                    'Discapacidad' => $request->Discapacidad,
                    'Grado_Discapacidad' => $request->Grado_Discapacidad,
                    'Tipo_Afiliado' => $request->Tipo_Afiliado,
                    'Parentesco' => $request->Parentesco,
                    'TipoDoc_Cotizante' => $request->TipoDoc_Cotizante,
                    'Doc_Cotizante' => $request->Doc_Cotizante,
                    'Tipo_Cotizante' => $request->Tipo_Cotizante,
                    'Estado_Afiliado' => $request->estadoPaciente,
                    'Dane_Mpio' => $request->Dane_Mpio,
                    'Mpio_Afiliado' => $request->Mpio_Afiliado,
                    'Dane_Dpto' => $request->Dane_Dpto,
                    'Departamento' => $request->Departamento,
                    'Subregion' => $request->Subregion,
                    'Dpto_Atencion' => $request->departamentoAtencion,
                    'Mpio_Atencion' => $request->municipioAtencion,
                    'IPS' => $request->sedeAtencion,
                    'Sede_Odontologica' => $request->Sede_Odontologica,
                    'Medicofamilia' => $request->Medicofamilia,
                    'Etnia' => $request->Etnia,
                    'Direccion_Residencia' => $request->Direccion_Residencia,
                    'Mpio_Residencia' => $request->Mpio_Residencia,
                    'Telefono' => $request->Telefono,
                    'Correo1' => $request->Correo1,
                    'Correo2' => $request->Correo2,
                    'Celular1' => $request->Celular1,
                    'Celular2' => $request->Celular2,
                    'entidad_id' => $request->entidad_id,
                    'portabilidad' => $request ->portabilidad
                ]);
                $mensaje = 'Paciente en Portabilidad Actualizado con Exito!';
                $estado = false;

            }
            return response()->json(['mensaje' => $mensaje, 'estado' => $estado]);
        }
        public function fechtPacientes(){
            $fechaHoy = date('Y-m-d');
            $paciente = Paciente::select(
            DB::raw("CONCAT(pacientes.Primer_Nom, '  ', pacientes.SegundoNom, '  ', pacientes.Primer_Ape, '  ', pacientes.Segundo_Ape) as Nombre"),
            'Tipo_Doc','Num_Doc','Ut','novedades_pacientes.fechaInicio_portabilidad','novedades_pacientes.fechaFinal_portabilidad'
            ,'novedades_pacientes.ipsorigen_portabilidad','pacientes.id')
            ->join('novedades_pacientes','novedades_pacientes.paciente_id','pacientes.id')
            ->where('portabilidad', 1)
            ->whereNotNull('novedades_pacientes.fechaFinal_portabilidad')
            ->where('novedades_pacientes.fechaFinal_portabilidad','>=',$fechaHoy)
            ->whereIn('Estado_Afiliado',[1,28,29])->get();

            foreach($paciente as $orden){
                $ordens = citapaciente::select(
                    DB::raw("COUNT(cupordens.id) as total"))
                ->join('ordens','ordens.Cita_id','cita_paciente.id')
                ->leftjoin('cupordens','cupordens.Orden_id','ordens.id')
                ->leftjoin('cups','cups.id','cupordens.Cup_id')
                ->where('cupordens.Estado_id',37)
                ->where('cita_paciente.Paciente_id',$orden->id)
                ->get();

                $orden['conteoServicio'] = $ordens;
            }

            foreach($paciente as $orden){
                $ordens = citapaciente::select(
                    DB::raw("COUNT(detaarticulordens.id) as total"))
                ->join('ordens','ordens.Cita_id','cita_paciente.id')
                ->leftjoin('detaarticulordens','detaarticulordens.Orden_id','ordens.id')
                ->leftjoin('codesumis','codesumis.id','detaarticulordens.codesumi_id')
                ->where('detaarticulordens.Estado_id',37)
                ->where('cita_paciente.Paciente_id',$orden->id)
                ->get();

                $orden['conteoMedicamento'] = $ordens;
            }
            return response()->json($paciente,200);
        }

        public function detallepaciente($id)
        {
            $paciente = Paciente::select(
                'pacientes.Region',
                'pacientes.Ut',
                'pacientes.Primer_Nom',
                'pacientes.SegundoNom',
                'pacientes.Primer_Ape',
                'pacientes.Segundo_Ape',
                'pacientes.Tipo_Doc',
                'pacientes.Num_Doc',
                'pacientes.Sexo',
                'pacientes.Fecha_Afiliado',
                'pacientes.Fecha_Naci',
                'pacientes.Edad_Cumplida',
                'pacientes.Discapacidad',
                'pacientes.Grado_Discapacidad',
                'pacientes.Tipo_Afiliado',
                'pacientes.Orden_Judicial',
                'pacientes.Num_Folio',
                'pacientes.Fecha_Emision',
                'pacientes.Parentesco',
                'pacientes.TipoDoc_Cotizante',
                'pacientes.Doc_Cotizante',
                'pacientes.Tipo_Cotizante',
                'pacientes.Dane_Mpio',
                'pacientes.Mpio_Afiliado',
                'pacientes.Dane_Dpto',
                'pacientes.Departamento',
                'pacientes.Subregion',
                'pacientes.Sede_Odontologica',
                'pacientes.Medicofamilia',
                'pacientes.Etnia',
                'pacientes.Nivel_Sisben',
                'pacientes.Laboraen',
                'pacientes.Mpio_Labora',
                'pacientes.Direccion_Residencia',
                'pacientes.Mpio_Residencia',
                'pacientes.Telefono',
                'pacientes.Correo1',
                'pacientes.Correo2',
                'pacientes.Estrato',
                'pacientes.Celular1',
                'pacientes.Celular2',
                DB::raw("case pacientes.portabilidad  when 1 then 'SI' else 'NO' end as portabilidad"),
                'pacientes.ciclo_vida',
                'pacientes.regional',
                'pacientes.tipo_vivienda',
                'pacientes.zona_vivienda',
                'pacientes.numero_habitaciones',
                'pacientes.numero_miembros',
                'pacientes.acceso_vivienda',
                'pacientes.seguridad_vivienda',
                'pacientes.hogar_con_agua',
                'pacientes.prepara_comida_con',
                'pacientes.vivienda_con_energia',
                'pacientes.mascota',
                'pacientes.religion',
                'pacientes.nombre_pareja',
                'pacientes.pareja_actual_padre',
                'pacientes.ocupacion_padre',
                'pacientes.grupo_sanguineo_padre',
                'pacientes.municipio_nacimiento',
                'pacientes.pais_nacimiento',
                'e.Nombre as estadoPaciente',
                'm.Nombre as municipioAtencion',
                'd.Nombre as departamentoAtencion',
                'se.Nombre as sedeAtencion',
                'en.nombre as entidad_id',
                'np.ipsorigen_portabilidad',
                'np.telefonoorigen_portabilidad',
                'np.correoorigen_portabilidad',
                'np.fechaInicio_portabilidad',
                'np.fechaFinal_portabilidad',
                'np.ipsdestino_portabilidad',
                'np.causa',
                'np.otra_causa'
                )
                ->join('estados as e','e.id','pacientes.Estado_Afiliado')
                ->join('sedeproveedores as se','se.id','pacientes.IPS')
                ->leftjoin('municipios as m','m.id','pacientes.Mpio_Atencion')
                ->leftjoin('departamentos as d','d.id','pacientes.Dpto_Atencion')
                ->join('entidades as en','en.id','pacientes.entidad_id')
                ->join('novedades_pacientes as np','np.paciente_id','pacientes.id')
                ->where('pacientes.id',$id)
                ->first();
                return response()->json($paciente,200);
            }

        public function inactivarpaciente($id){
            $hoy =new DateTime();
            $paciente = Paciente::where('id', $id)->first();
            $paciente->update([
                'Estado_Afiliado'=> 27,
            ]);
            NovedadesPaciente::create([
                'paciente_id' => $paciente->id,
                'Tipo_id' => 37,
                'fecha_novedad' => $hoy,
                'fecha_creacion' => $hoy,
                'movtivo' => 'Retiro por finalización de portabilidad',
                'user_id' => auth()->id(),
                'estado_id' => 1,
        ]);
            $mensaje = 'Paciente se inactivo con Exito!';
            return response()->json(['mensaje' => $mensaje]);
        }

        public function historialAtencion(Request $request)
    {
        $citapaciente = citapaciente::select('cita_paciente.id as cita_paciente_id',
        DB::raw("CONCAT(u.name,' ',u.apellido) as usuario"),
        'cita_paciente.Tipocita_id','cita_paciente.Datetimeingreso',
        'u.especialidad_medico as Especialidad','t.Nombre as Tipocita','ta.name as Atividad'
        )
        ->join('users as u','cita_paciente.user_medico_atiende','u.id')
        ->JOIN('pacientes as b','cita_paciente.Paciente_id','b.id')
        ->join('tipocitas as t','cita_paciente.Tipocita_id','t.id')
        ->join('conductas as f','cita_paciente.id','f.Citapaciente_id')
        ->join('tipo_agendas as ta','ta.id','cita_paciente.actividad_id')
        ->where('cita_paciente.Paciente_id',$request['paciente_id'])
        ->where('cita_paciente.Estado_id', 9)
        ->where('cita_paciente.actividad_id',$request['Tipo_agenda'])
        ->where('cita_paciente.user_medico_atiende',auth()->id())
        ->orderBy('cita_paciente.datetimeingreso', 'desc')
        ->get();
        return response()->json($citapaciente, 200);
    }

    public function buscarDatosAlert(request $request)
    {
        $alertasMedicamentos = AntecedenteFarmacologico::select('d.Producto','antecedente_farmacologicos.observacionAlergia',
        DB::raw("CONCAT(u.name,' ',u.apellido) as usuario"),'antecedente_farmacologicos.created_at as fecha')
        ->join('detallearticulos as d','d.id','antecedente_farmacologicos.detallearticulo_id')
        ->join('users as u','antecedente_farmacologicos.usuario_id','u.id')
        ->where('antecedente_farmacologicos.estado_id',1)
        ->where('antecedente_farmacologicos.paciente_id',$request['paciente_id'])->get();

        $alertasAlimentos = AntecedenteFarmacologico::select('antecedente_farmacologicos.Alimneto','antecedente_farmacologicos.observacionalimneto',
        DB::raw("CONCAT(u.name,' ',u.apellido) as usuario"),'antecedente_farmacologicos.created_at as fecha')
        ->join('users as u','antecedente_farmacologicos.usuario_id','u.id')
        ->where('antecedente_farmacologicos.estado_id',1)
        ->whereNotNull('antecedente_farmacologicos.Alimneto')
        ->where('antecedente_farmacologicos.paciente_id',$request['paciente_id'])->get();

        $alertasAmbientales = AntecedenteFarmacologico::select('antecedente_farmacologicos.Ambientales','antecedente_farmacologicos.observacionambiental',
        DB::raw("CONCAT(u.name,' ',u.apellido) as usuario"),'antecedente_farmacologicos.created_at as fecha')
        ->join('users as u','antecedente_farmacologicos.usuario_id','u.id')
        ->where('antecedente_farmacologicos.estado_id',1)
        ->whereNotNull('antecedente_farmacologicos.Ambientales')
        ->where('antecedente_farmacologicos.paciente_id',$request['paciente_id'])->get();

        $alertasOtras = AntecedenteFarmacologico::select('antecedente_farmacologicos.OtrasAlergias','antecedente_farmacologicos.observacionotras',
        DB::raw("CONCAT(u.name,' ',u.apellido) as usuario"),'antecedente_farmacologicos.created_at as fecha')
        ->join('users as u','antecedente_farmacologicos.usuario_id','u.id')
        ->where('antecedente_farmacologicos.estado_id',1)
        ->whereNotNull('antecedente_farmacologicos.OtrasAlergias')
        ->where('antecedente_farmacologicos.paciente_id',$request['paciente_id'])->get();

        return response()->json([
            'Medicamentos'  => $alertasMedicamentos,
            'Alimentos'     => $alertasAlimentos,
            'Ambientales'   => $alertasAmbientales,
            'Otros'         => $alertasOtras
        ], 200);
    }

    public function conteoAlertas(request $request)
    {
        $conteoAlertas = AntecedenteFarmacologico::select(DB::raw("COUNT(antecedente_farmacologicos.id) as count"))
        ->where('antecedente_farmacologicos.estado_id',1)
        ->where('antecedente_farmacologicos.paciente_id',$request['paciente_id'])->first();

        return response()->json($conteoAlertas);
    }

    public function historialLabortario(Request $request){

        $laboratorio = Orden_cabecera::select('orden_cabeceras.num_orden','orden_cabeceras.fecha','orden_cabeceras.nom_medico',
        'orden_cabeceras.urgente','sedeproveedores.Nombre','orden_detalle.nom_examen',
        'orden_detalle.cod_examen','orden_detalle.estado_cargado','orden_detalle.estado_resultado')
        ->join('sedeproveedores','sedeproveedores.id','orden_cabeceras.id_laboratorio')
        ->join('orden_detalle','orden_detalle.num_orden','orden_cabeceras.num_orden')
        ->where('orden_cabeceras.documento',$request['cedula'])
        ->distinct()
        ->get();

        return response()->json($laboratorio);

    }

    public function buscarResultado(Request $request){
        $laboratorio = Resultados_annarlab::select('resultados_annarlabs.*')
        ->where('resultados_annarlabs.num_orden',$request['num_orden'])
        ->where('resultados_annarlabs.cod_examen',$request['cod_examen'])
        ->distinct()
        ->get();

        return response()->json($laboratorio);
    }

    public function conteoLaboratorio(Request $request)
    {
        $laboratorio = Orden_cabecera::select(
            DB::raw("COUNT(orden_cabeceras.num_orden) as count"),
        )
        ->where('orden_cabeceras.documento',$request['cedula'])
        ->distinct()
        ->first();
        return response()->json($laboratorio);
    }

    public function auditoriaDescarga(Request $request){
        $auditoria = AuditoriaDescarga::create([
            'user_id' => auth()->id(),
    ]);
        return response()->json($auditoria);
    }

    public function buscarDatosExam(Request $request){

        $lab1 = Orden_cabecera::select('orden_cabeceras.num_orden','orden_cabeceras.fecha','orden_cabeceras.nom_medico',
        'orden_cabeceras.urgente','sedeproveedores.Nombre','orden_detalle.nom_examen',
        'orden_detalle.cod_examen','orden_detalle.estado_cargado','orden_detalle.estado_resultado')
        ->join('sedeproveedores','sedeproveedores.id','orden_cabeceras.id_laboratorio')
        ->join('orden_detalle','orden_detalle.num_orden','orden_cabeceras.num_orden')
        ->where('orden_cabeceras.documento',$request['Num_Doc'])
        ->distinct()
        ->get();

        $lab2 = ResultadosLaboratorio::select([
            DB::raw("('H.clinica') as num_orden"),
            "resultados_laboratorios.fecha_laboratorio as fecha",
            "resultados_laboratorios.laboratorio as nom_examen",
            DB::raw("CONCAT(u.name,' ',u.apellido) as nom_medico"),
            "resultados_laboratorios.resultado_lab",
            DB::raw("(2) as estado_cargado"),
            DB::raw("(1) as estado_resultado"),
            "resultados_laboratorios.adjunto"
        ])->join('pacientes as a','a.id','resultados_laboratorios.paciente_id')
            ->join('users as u','u.id','resultados_laboratorios.usuario_id')
            ->where('a.Num_Doc',$request['Num_Doc'])
            ->get();

        $laboratorio = array_merge($lab1->toArray(),$lab2->toArray());

        return response()->json($laboratorio);

    }

    public function portabilidadSalida(Request $request){
        $hoy =new DateTime();
        NovedadesPaciente::create([
            'paciente_id' => $request->id,
            'Tipo_id' => 40,
            'fecha_novedad' => $hoy,
            'fecha_creacion' => $hoy,
            'movtivo' => 'Paciente en Portabilidad de salida',
            'user_id' => auth()->id(),
            'estado_id' => 1,
            'ipsdestino_portabilidad' => $request->entidadReceptora,
            'fechaInicio_portabilidad' => $request->fechaInicio_portabilidad,
            'fechaFinal_portabilidad' => $request->fechaFinal_portabilidad,
            'departamentoReceptor' => $request->departamentoReceptor,
            'municipio_receptor' => $request->municipio_receptor,
            'causa' => $request->causa,
            'otra_causa' => $request->otra_causa,
        ]);
        $mensaje = 'La portabildiad de salida se a creado con Exito!';
        return response()->json(['mensaje' => $mensaje]);
    }

    public function fechtPacientesPortabilidadSalida(){
        $fechaHoy = date('Y-m-d');
        $paciente = Paciente::select(
        DB::raw("CONCAT(pacientes.Primer_Nom, '  ', pacientes.SegundoNom, '  ', pacientes.Primer_Ape, '  ', pacientes.Segundo_Ape) as Nombre"),
        'Tipo_Doc',
        'Num_Doc',
        'Ut',
        'novedades_pacientes.fechaInicio_portabilidad',
        'novedades_pacientes.fechaFinal_portabilidad',
        'novedades_pacientes.municipio_receptor',
        DB::raw("case novedades_pacientes.ipsdestino_portabilidad
        when 1 then 'Unión Temporal Tolihuila'
        when 2 then 'Cosmitet Región 2'
        when 3 then 'Unión Temporal Salud Sur 2'
        when 4 then 'Unión Temporal Medisalud'
        when 5 then 'Unión Temporal del Norte Región 5'
        when 6 then 'Organización clínica general del norte'
        when 7 then 'Unión Temporal Red Integrada Foscal-CUB'
        when 8 then 'Red Vital UT'
        when 9 then 'Cosmitet Región 9'
        when 10 then 'Unión Temporal Servisalud San Jose' end ipsdestino_portabilidad"),
        'pacientes.id','novedades_pacientes.id as novedadId')
        ->join('novedades_pacientes','novedades_pacientes.paciente_id','pacientes.id')
        ->whereNotNull('novedades_pacientes.fechaFinal_portabilidad')
        ->where('Tipo_id',40)
        ->where('estado_id',1)
        ->where('novedades_pacientes.fechaFinal_portabilidad','>=',$fechaHoy)
        ->whereIn('Estado_Afiliado',[1,28,29])->get();
        return response()->json($paciente,200);
    }

    public function detallepacientePortabilidad($id)
        {
            $paciente = Paciente::select(
                'pacientes.id as paciente_id',
                'pacientes.Region',
                'pacientes.Ut',
                'pacientes.Primer_Nom',
                'pacientes.SegundoNom',
                'pacientes.Primer_Ape',
                'pacientes.Segundo_Ape',
                'pacientes.Tipo_Doc',
                'pacientes.Num_Doc',
                'pacientes.Sexo',
                'pacientes.Fecha_Afiliado',
                'pacientes.Fecha_Naci',
                'pacientes.Edad_Cumplida',
                'pacientes.Discapacidad',
                'pacientes.Grado_Discapacidad',
                'pacientes.Tipo_Afiliado',
                'pacientes.Orden_Judicial',
                'pacientes.Num_Folio',
                'pacientes.Fecha_Emision',
                'pacientes.Parentesco',
                'pacientes.TipoDoc_Cotizante',
                'pacientes.Doc_Cotizante',
                'pacientes.Tipo_Cotizante',
                'pacientes.Dane_Mpio',
                'pacientes.Mpio_Afiliado',
                'pacientes.Dane_Dpto',
                'pacientes.Departamento',
                'pacientes.Subregion',
                'pacientes.Sede_Odontologica',
                'pacientes.Medicofamilia',
                'pacientes.Etnia',
                'pacientes.Nivel_Sisben',
                'pacientes.Laboraen',
                'pacientes.Mpio_Labora',
                'pacientes.Direccion_Residencia',
                'pacientes.Mpio_Residencia',
                'pacientes.Telefono',
                'pacientes.Correo1',
                'pacientes.Correo2',
                'pacientes.Estrato',
                'pacientes.Celular1',
                'pacientes.Celular2',
                DB::raw("case pacientes.portabilidad  when 1 then 'SI' else 'NO' end as portabilidad"),
                'pacientes.ciclo_vida',
                'pacientes.regional',
                'pacientes.tipo_vivienda',
                'pacientes.zona_vivienda',
                'pacientes.numero_habitaciones',
                'pacientes.numero_miembros',
                'pacientes.acceso_vivienda',
                'pacientes.seguridad_vivienda',
                'pacientes.hogar_con_agua',
                'pacientes.prepara_comida_con',
                'pacientes.vivienda_con_energia',
                'pacientes.mascota',
                'pacientes.religion',
                'pacientes.nombre_pareja',
                'pacientes.pareja_actual_padre',
                'pacientes.ocupacion_padre',
                'pacientes.grupo_sanguineo_padre',
                'pacientes.municipio_nacimiento',
                'pacientes.pais_nacimiento',
                'e.Nombre as estadoPaciente',
                'm.Nombre as municipioAtencion',
                'd.Nombre as departamentoAtencion',
                'se.Nombre as sedeAtencion',
                'en.nombre as entidad_id',
                'np.ipsorigen_portabilidad',
                'np.telefonoorigen_portabilidad',
                'np.correoorigen_portabilidad',
                'np.fechaInicio_portabilidad',
                'np.fechaFinal_portabilidad',
                'np.ipsdestino_portabilidad',
                'np.causa',
                'np.otra_causa',
                'np.id as id_novedad',
                'np.departamentoReceptor',
                'np.municipio_receptor'
                )
                ->join('estados as e','e.id','pacientes.Estado_Afiliado')
                ->join('sedeproveedores as se','se.id','pacientes.IPS')
                ->leftjoin('municipios as m','m.id','pacientes.Mpio_Atencion')
                ->leftjoin('departamentos as d','d.id','pacientes.Dpto_Atencion')
                ->join('entidades as en','en.id','pacientes.entidad_id')
                ->join('novedades_pacientes as np','np.paciente_id','pacientes.id')
                ->where('pacientes.id',$id)
                ->orderby('np.id','desc')
                ->first();

                return response()->json($paciente,200);
            }

    public function actualizarPacientePortabilidad($id,Request $request){
        $novedad = NovedadesPaciente::where('id',$id)->first();
        $novedad->update([
            'ipsdestino_portabilidad' => $request->entidadReceptora,
            'fechaInicio_portabilidad' => $request->fechaInicio_portabilidad,
            'fechaFinal_portabilidad' => $request->fechaFinal_portabilidad,
            'departamentoReceptor' => $request->departamentoReceptor,
            'causa' => $request->causa,
            'otra_causa' => $request->otra_causa,
        ]);
        $mensaje = 'La portabildiad de salida se a actualizado con Exito!';
        return response()->json(['mensaje' => $mensaje]);
    }

    public function inactivaPrortabilidad($id){
        $novedad = NovedadesPaciente::where('id',$id)->first();
        $novedad->update([
            'estado_id' => 16,
        ]);
        $mensaje = 'La portabildiad de salida se a actualizado con Exito!';
        return response()->json(['mensaje' => $mensaje]);
    }

    /**
     * Consulta un usuario - ruta abierta para 3ros
     * @param Request $request
     * @param string $document
     * @return Response
     */
    public function consult(Request $request, $document){
        try{

            $paciente = Paciente::where('Num_Doc', $document)->first();
            if(!$paciente){
                throw new Error("El paciente no existe.", 404);
            }

            /** Creamos el objeto del servicio */
            $paciente_servicio = new PacienteService();
            $json = $paciente_servicio->generarEstructuraHL7($paciente);

            return response()->json($json);

        }catch(\Throwable $th){
            if($th->getCode()){
                return response()->json($th->getMessage(), $th->getCode());
            }
            return response()->json($th->getMessage(),400);
        }
    }

    public function buscarOrdenesPortabilidad($id){

        try{
            $ordens = citapaciente::select(
                'ordens.id as orden',
                'cups.Codigo as codigoServicio',
                'cups.Nombre as servicio',
                'cupordens.id as cuporden_id',
                DB::RAW("CONVERT(varchar,cupordens.fecha_orden,103) as fechaServicio"),
                'detaarticulordens.id as detallearticulo_id',
                DB::RAW("CONVERT(varchar,detaarticulordens.Fechaorden,103) as fechaMedicamento"),
                'codesumis.Nombre as medicamento',
                'codesumis.Codigo as codigoMedicamento',
                'cita_paciente.id as citapaciente_id',
                'cita_paciente.Paciente_id')
            ->join('ordens','ordens.Cita_id','cita_paciente.id')
            ->leftjoin('cupordens','cupordens.Orden_id','ordens.id')
            ->leftjoin('cups','cups.id','cupordens.Cup_id')
            ->leftjoin('detaarticulordens','detaarticulordens.Orden_id','ordens.id')
            ->leftjoin('codesumis','codesumis.id','detaarticulordens.codesumi_id')
            ->where('cupordens.Estado_id',37)
            ->where('cita_paciente.Paciente_id',$id)
            ->orderBy('ordens.id','desc')
            ->get();

            return response()->json($ordens, 200);

        }catch(\Throwable $th){
            if($th->getCode()){
                return response()->json($th->getMessage(), $th->getCode());
            }
            return response()->json($th->getMessage(),400);
        }
    }

    public function buscarMedicamentosPortabilidad($id){

        try{

            $ordens = citapaciente::select(
                'ordens.id as orden',
                'detaarticulordens.id as detallearticulo_id',
                DB::RAW("CONVERT(varchar,detaarticulordens.Fechaorden,103) as fechaMedicamento"),
                'codesumis.Nombre as medicamento',
                'codesumis.Codigo as codigoMedicamento',
                'cita_paciente.id as citapaciente_id',
                'cita_paciente.Paciente_id')
            ->join('ordens','ordens.Cita_id','cita_paciente.id')
            ->leftjoin('detaarticulordens','detaarticulordens.Orden_id','ordens.id')
            ->leftjoin('codesumis','codesumis.id','detaarticulordens.codesumi_id')
            ->where('detaarticulordens.Estado_id',37)
            ->where('cita_paciente.Paciente_id',$id)
            ->orderBy('ordens.id','desc')
            ->get();

            return response()->json($ordens, 200);

        }catch(\Throwable $th){
            if($th->getCode()){
                return response()->json($th->getMessage(), $th->getCode());
            }
            return response()->json($th->getMessage(),400);
        }
    }

    public function cambioEstado(Request $request){
        try{
            if($request->codigoServicio != null){
                $servicio = Cuporden::where('id',$request->cuporden_id)->first();
                $servicio->update(['Estado_id' => 15]);
                return response()->json(['mensaje' => 'Se paso la orden a auditoria'], 200);
            }else{
                $medicamento = Detaarticulorden::where('id',$request->detallearticulo_id)->first();
                $medicamento->update(['Estado_id' => 15]);
                return response()->json(['mensaje' => 'Se paso la orden a auditoria'], 200);
            }
        }catch(\Throwable $th) {
            if($th->getCode()){
                return response()->json($th->getMessage(), $th->getCode());
            }
            return response()->json($th->getMessage(),400);
        }
    }

    /**
     * Almacena y/o actualiza la firma y foto del paciente para los consentimientos informados
     * @param Request $request
     * @return Response
     * @author David Peláez
     */
    public function guardarFotoFirma(Request $request){
        try{

            /** Buscamos el cita paciente */
            $cita_paciente = citapaciente::where('id', $request->cita_paciente_id)->first();

            /** Se genera la imagen apartir de una base64 */
            $foto = base64_decode(explode(',', $request->foto)[1]);
            $firma_paciente = base64_decode(explode(',', $request->firma_paciente)[1]);
            $firma_acudiente = $request->firma_acudiente ? base64_decode(explode(',', $request->firma_acudiente)[1]) : null;

            /** Se genera una ruta unica para el archivo */
            $ruta_foto = 'firmaConsentimientos/fotos/' . uniqid() . '.png';
            $ruta_foto_guardar = '/storage/app/public/'. $ruta_foto;
            Storage::disk('public')->put($ruta_foto, $foto);
            $ruta_firma_paciente = 'firmaConsentimientos/firmas/' . uniqid() . '.png';
            $ruta_firma_paciente_guardar = '/storage/app/public/' .  $ruta_firma_paciente;
            Storage::disk('public')->put($ruta_firma_paciente, $firma_paciente);

            $ruta_firma_acudiente = "";
            $ruta_firma_acudiente_guardar ="";
            if($firma_acudiente){
                $ruta_firma_acudiente = 'firmaConsentimientos/firmas/' . uniqid() . '.png';
                $ruta_firma_acudiente_guardar = '/storage/app/public/'.$ruta_firma_acudiente;
                Storage::disk('public')->put($ruta_firma_acudiente, $firma_acudiente);
            }

            /** Si se almaceno se guarda la ruta en base de datos */
            $cita_paciente->update([
                'ruta_foto' => $ruta_foto_guardar,
                'firma_paciente' => $ruta_firma_paciente_guardar,
                'firma_acudiente' => $ruta_firma_acudiente_guardar,
                'aceptacion_consentimiento'=> $request->aceptacion_consentimiento,
                'firmante'=> $request->firmante,
                'numero_documento_representante'=> $request->numero_documento_representante,
            ]);

            return response()->json($cita_paciente);
        }catch(\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function agregarBarreras(Request $request){
        $agregaBarrera = barreraAcceso::create([
            'barrera' => $request->barrera,
            'observacion' => $request->observacion,
            'user_id' => auth()->id(),
            'paciente_id' => $request->paciente_id,
        ]);
        return response()->json([
            'message' => 'Barrera de acceso creada con exito!',
        ], 201);
    }

    public function listarBarreras($id){
        $listarBarrera = barreraAcceso::select('barrera_accesos.*',
        DB::raw("CONCAT(users.name,' ',users.apellido) as usuario"),
        'estados.nombre as estado')
        ->join('users','users.id','barrera_accesos.user_id')
        ->join('estados','estados.id','barrera_accesos.estado_id')
        ->where('estados.id',1)
        ->where('barrera_accesos.paciente_id', $id)->get();
        return response()->json($listarBarrera, 200);
    }

    public function solucionarBarreras(request $request){
        $buscarBarrera = barreraAcceso::where('id',  $request->id)->first();
        $buscarBarrera->update([
            'estado_id' => 13,
            'observacion_cierre' => $request->observacion
        ]);
        return response()->json([
            'message' => 'Barrera de acceso cerrado con exito!',
        ], 200);
    }

    public function novedadesAll(){

        $Novedades = Tipo::where("Descripcion","Novedades")
        ->where("Estado_id",1)->get();

        return response()->json(['Novedades' =>$Novedades,], 200);
    }

    /**
     * Funcion para marcar los pacientes
     * @param Request $request
     * @return Response
     * @author kobatime
     */
    public function actualizacionMasiva(int $id,Request $request){
        try{
            if($id == 1){
                $users = auth()->id();
                $archivos = $request->file('files');
                $fileName = 'BDHorus.csv';
                $ruta_011= '/Cargue_Archivos/Usuarios_Hosvital/'.$fileName;
                Storage::disk(config('filesystems.disksUse'))->put($ruta_011,fopen($archivos[0], 'r+'));
                $ejecutarProcedimiento = collect(DB::select("SET NOCOUNT ON exec dbo.sp_NovedadesDiariasUsFomag ?",[$users]));
                Storage::disk(config('filesystems.disksUse'))->delete($ruta_011);

                return response()->json([
                    'data' => $ejecutarProcedimiento,
                    'message' => 'Actualización con exito de FOMAG!',
                ], 200);
            }
            else if($id != 1){
                return response()->json([
                    'message' => 'Todavia no se puede realizar carga masiva de esta entidad',
                ], 400);
            }
        }catch(\Exception $th){
            return response()->json([
                'res' => false,
                'error' => $th->getMessage(),
                'mensaje' => 'Error al intentar actualizar novedades!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request
     * @param string $cedula
     * @return Response
     * @author Juancho Rois
     */
    public function consultarPacientes(Request $request, $cedula)
    {
        try {
            $paciente = Paciente::select(
                'pacientes.id','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
                'pacientes.Segundo_Ape','pacientes.Tipo_Doc', 'pacientes.Nivelestudio',
                'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Naci','pacientes.Edad_Cumplida'
                ,'pacientes.Tipo_Afiliado','pacientes.Estado_Afiliado',
                'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
                'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
                'pacientes.Correo1','pacientes.Correo2','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
                'pacientes.Num_Hijos','pacientes.entidad_id','pacientes.anexo','pacientes.ciclo_vida','pacientes.regional','sedeproveedores.Nombre as NombreIPS',
                'e.nombre as entidad', DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"))
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->leftjoin('users','users.id','pacientes.Medicofamilia')
                ->where('Num_Doc', $cedula)
                ->first();
                return response()->json([
                    'data' => $paciente,
                    'message' => 'Actualización con exito de FOMAG!',
                ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'error' => $th->getMessage(),
                'mensaje' => 'Error al intentar recuperar la información!'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function pacienteConcurrencia($cedula)
    {
        $paciente = Paciente::where('Num_Doc', $cedula)->first();
        if (!$paciente) {
            return response()->json([
                'message' => 'El paciente no existe en el sistema',
            ], 201);
        }else if($paciente->entidad_id == 4){
            return response()->json([
                'message' => 'El paciente no pertenece al contrato magisterio o ferrocarril',
            ], 201);
        }
        $concurrencia = Registroconcurrencia::where('paciente_id',$paciente->id)->where('estado_id',1)->first();
        if($concurrencia){
            return response()->json([
                'message' => 'El paciente tiene un ingreso abierto en concurrencia!',
            ], 201);
        }
        else {
            $pacienteNovedad = Paciente::select(
                'pacientes.id','pacientes.Region','pacientes.Ut','pacientes.Primer_Nom','pacientes.SegundoNom','pacientes.Primer_Ape',
                'pacientes.Segundo_Ape','pacientes.Tipo_Doc',
                'pacientes.Num_Doc','pacientes.Sexo','pacientes.Fecha_Afiliado','pacientes.Fecha_Naci','pacientes.Edad_Cumplida','pacientes.Discapacidad',
                'pacientes.Grado_Discapacidad','pacientes.Tipo_Afiliado','pacientes.Orden_Judicial','pacientes.Num_Folio','pacientes.Fecha_Emision',
                'pacientes.Parentesco','pacientes.TipoDoc_Cotizante','pacientes.Doc_Cotizante','pacientes.Tipo_Cotizante','pacientes.Estado_Afiliado',
                'pacientes.Dane_Mpio','pacientes.Mpio_Afiliado','pacientes.Dane_Dpto','pacientes.Departamento','pacientes.Subregion','pacientes.Dpto_Atencion',
                'pacientes.Mpio_Atencion','pacientes.IPS','pacientes.Sede_Odontologica','pacientes.Medicofamilia',DB::raw("CONCAT(users.name,' ',users.apellido) as medico_familia"),'pacientes.Etnia','pacientes.Nivel_Sisben',
                'pacientes.Laboraen','pacientes.Mpio_Labora','pacientes.Direccion_Residencia','pacientes.Mpio_Residencia','pacientes.Telefono',
                'pacientes.Correo1','pacientes.Correo2','pacientes.Estrato','pacientes.Celular1','pacientes.Celular2','pacientes.Sexo_Biologico','pacientes.Tipo_Regimen',
                'pacientes.Num_Hijos','pacientes.Vivecon','pacientes.RH','pacientes.Tienetutela','pacientes.Nivelestudio','pacientes.Nombreacompanante',
                'pacientes.Telefonoacompanante','pacientes.Nombreresponsable','pacientes.Telefonoresponsable','pacientes.Aseguradora',
                'pacientes.Tipovinculacion','pacientes.Ocupacion','pacientes.nivel','pacientes.entidad_id','pacientes.vlr_upc','pacientes.fecha_ini_cont',
                'pacientes.fecha_fin_cont','pacientes.valor_cont_cap','pacientes.valot_cont_pyp','pacientes.sem_cot','pacientes.tipo_categoria',
                'pacientes.ut_saliente','pacientes.estado_civil','pacientes.dx','pacientes.cups','pacientes.cums','pacientes.propios','pacientes.f_solicitud','pacientes.anexo',
                'pacientes.ruta','pacientes.represa','pacientes.justifica_represa','pacientes.observacion_contratista','pacientes.cargo_laboral',
                'pacientes.composicion_familiar','pacientes.vivienda','pacientes.personas_a_cargo','pacientes.tipo_contratacion',
                'pacientes.antiguedad_en_empresa','pacientes.antiguedad_cargo_actual','pacientes.numero_cursos_a_cargo',
                'pacientes.password','pacientes.portabilidad','pacientes.ciclo_vida','pacientes.regional','pacientes.tipo_vivienda','pacientes.zona_vivienda',
                'pacientes.numero_habitaciones','pacientes.numero_miembros','pacientes.acceso_vivienda','pacientes.seguridad_vivienda',
                'pacientes.hogar_con_agua','pacientes.prepara_comida_con','pacientes.vivienda_con_energia','pacientes.mascota','pacientes.religion',
                'pacientes.nombre_pareja','pacientes.pareja_actual_padre','pacientes.ocupacion_padre','pacientes.grupo_sanguineo_padre',
                'pacientes.municipio_nacimiento','pacientes.pais_nacimiento','pacientes.Otradiscapacidad','pacientes.victima_conficto_armado',
                'pacientes.domiciliaria','pacientes.edad_horus','pacientes.fecha_nacimiento_horus',
                'sedeproveedores.Nombre as NombreIPS', 'municipios.Nombre as NombreMunicipio',
                'e.nombre as entidad','departamentos.Nombre as NombreDepartamento','sedeproveedores.id as Sedeprestador_id')
                ->leftjoin('sedeproveedores', 'pacientes.IPS', 'sedeproveedores.id')
                ->leftjoin('municipios','municipios.id','pacientes.Mpio_Atencion')
                ->leftjoin('departamentos','departamentos.id','municipios.Departamento_id')
                ->leftjoin('users','users.id','pacientes.Medicofamilia')
                ->join('entidades as e', 'pacientes.entidad_id', 'e.id')
                ->where('pacientes.id', $paciente->id)
                ->first();



            return response()->json([
                    'paciente' => $pacienteNovedad]
                , 200);
        }
    }

    public function pacienteConcurrenciaAlta($cedula){
        $concurrencias = Registroconcurrencia::select('registroconcurrencias.*',
        'registroconcurrencias.id as registroconcurrencias_id', 'p.*',
        DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom,' ',p.Primer_Ape,' ',p.Segundo_Ape) AS paciente_nombre"),
        DB::raw("CONCAT(u.name,' ',u.apellido) AS auditor"),
        DB::raw("CONCAT(us.name,' ',us.apellido) AS medico_familia"),'s.Nombre as NombreIPS',
        DB::raw("DATEDIFF(day,registroconcurrencias.fecha_ingreso_concurrencia,registroconcurrencias.fecha_egreso) AS dias_estancia"))
            ->join('Pacientes as p', 'registroconcurrencias.Paciente_id', 'p.id')
            ->join('sedeproveedores as s', 's.id','p.IPS')
            ->leftjoin('users as us','us.id','p.Medicofamilia')
            ->join('users as u', 'registroconcurrencias.auditor_id', 'u.id')
            ->where('registroconcurrencias.estado_id',44)
            ->where('Num_Doc', $cedula)->get();

            return response()->json($concurrencias);
    }

    public function buscarDatosConsentimientos(Request $request)
    {
        $paciente = Paciente::where('Num_Doc', $request->Num_Doc)->first();

        $consentimiento = citapaciente::select('tipo_agendas.*',
        'cita_paciente.id as citaPaciente_id',
        'citas.Hora_Inicio',
        'tipo_agendas.name as actividad',
        'tipo_agendas.modalidad',
        DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) AS paciente_nombre"),
        DB::RAW("concat(users.name,' ',users.apellido) as medico"),
        )
        ->join('citas','citas.id','cita_paciente.Cita_id')
        ->join('agendas','agendas.id','citas.Agenda_id')
        ->join('especialidade_tipoagenda','especialidade_tipoagenda.id','agendas.Especialidad_id')
        ->join('tipo_agendas','tipo_agendas.id','especialidade_tipoagenda.Tipoagenda_id')
        ->join('especialidades','especialidades.id','especialidade_tipoagenda.Especialidad_id')
        ->join('pacientes','pacientes.id','cita_paciente.Paciente_id')
        ->join('users','users.id','cita_paciente.user_medico_atiende')
        ->where('cita_paciente.estado_id',9)
        ->whereNotNull('cita_paciente.firma_consentimiento')
        ->where('cita_paciente.Paciente_id',$paciente->id)->get();

        return response()->json($consentimiento);
    }

    public function portabilidadHistorico($cedulaHistorico){
        $fechaHoy = date('Y-m-d');
        $paciente = Paciente::select(
        DB::raw("CONCAT(pacientes.Primer_Nom, '  ', pacientes.SegundoNom, '  ', pacientes.Primer_Ape, '  ', pacientes.Segundo_Ape) as Nombre"),
        'Tipo_Doc','Num_Doc','Ut','novedades_pacientes.fechaInicio_portabilidad','novedades_pacientes.fechaFinal_portabilidad'
        ,'novedades_pacientes.ipsorigen_portabilidad','pacientes.id')
        ->join('novedades_pacientes','novedades_pacientes.paciente_id','pacientes.id')
        ->whereNotNull('novedades_pacientes.fechaFinal_portabilidad')
        ->where('novedades_pacientes.fechaFinal_portabilidad','<=',$fechaHoy)
        ->where('pacientes.Num_Doc',$cedulaHistorico)->get();

        return response()->json($paciente,200);
    }

}
