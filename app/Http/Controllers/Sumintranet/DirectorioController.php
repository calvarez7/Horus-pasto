<?php

namespace App\Http\Controllers\Sumintranet;

use App\Http\Controllers\Controller;
use App\Modelos\Area;
use App\Modelos\Empleado;
use App\Modelos\empleado_familia;
use App\Modelos\eps;
use App\Modelos\Tipofamiliar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DirectorioController extends Controller
{
    public function index()
    {
        $empleado = Empleado::select(
            'empleados.id',
            'empleados.primer_nombre',
            'empleados.segundo_nombre',
            'empleados.primer_apellido',
            'empleados.segundo_apellido',
            'empleados.Nombre',
            'empleados.Documento',
            'empleados.correo',
            'empleados.celular',
            'empleados.tipo_documento',
            'empleados.lugar_nacimineto',
            'empleados.lugar_exp_documento',
            'empleados.fecha_exp_documento',
            'empleados.fecha_nacimiento',
            'empleados.nombre_documento',
            'empleados.ruta_documento',
            'empleados.genero',
            'empleados.grupo_sanguineo',
            'empleados.etnia',
            'empleados.discapacidad',
            'empleados.tipo_discapacidad',
            'empleados.cabeza_familia',
            'empleados.estado_civil',
            'empleados.estatura',
            'empleados.peso',
            'empleados.telefono',
            'empleados.celular2',
            'empleados.correo_corporativo',
            'empleados.direccion_residencia',
            'empleados.barrio',
            'empleados.tipo_vivienda',
            'empleados.estrato',
            'empleados.contacto_emergencia',
            'empleados.tel_contacto_emergencia',
            'empleados.parentesco_contacto',
            'empleados.nombre_foto',
            'empleados.ruta_foto',
            'empleados.salario',
            'empleados.salario',
            'p.nombre as Nombre_eps',
            'p.id as ideps',
            'a.nombre as Nombre_area',
            'a.id as idarea',
            'm.Nombre as Nombre_municipio',
            'm.id as idmunici',
            'e.nombre as Nombre_estado',
            'e.id as idestado',
            'users.id as idusuario'
        )->with(['familia' => function ($query) {
            $query->select(
                'empleado_familia.id as idFamiliar',
                'empleado_familia.nombre as nombreFamliar',
                'empleado_familia.familiar_id',
                'empleado_familia.empleado_id',
                'empleado_familia.tipo_documento',
                'empleado_familia.num_documento',
                'empleado_familia.fecha_nacimiento',
                'empleado_familia.genero',
                'empleado_familia.escolaridad',
                'empleado_familia.profesion',
                'empleado_familia.depende_empleado',
                'empleado_familia.vivecon_el_empleado'
            )->leftjoin('empleados', 'empleados.id', 'empleado_familia.empleado_id')
                ->leftjoin('familiares', 'familiares.id', 'empleado_familia.familiar_id')->get();
        }])
            ->leftjoin('estados as e', 'e.id', 'empleados.Estado_id')
            ->leftjoin('eps as p', 'p.id', 'empleados.eps_id')
            ->leftjoin('areas as a', 'a.id', 'empleados.area_id')
            ->leftjoin('municipios as m', 'm.id', 'empleados.municipio_id')
            ->leftjoin('users', 'users.cedula', 'empleados.Documento')
            ->orderby('empleados.Estado_id','asc')
            ->orderby('empleados.id', 'asc')->get();
        return response()->json($empleado, 200);
    }

    public function listarfamilia($id)
    {
        $familia = empleado_familia::select([
            'users.id as idusuario',
            'empleado_familia.id', 'empleado_familia.nombre as nombre_Familiar', 'empleado_familia.genero', 'empleado_familia.edad',
            'empleados.id as idEmpleado', 'empleados.Nombre as nombreEmpleado', 'familiares.id as idFamiliares', 'familiares.nombre as nombreFamilia',
            'empleado_familia.fecha_nacimiento', 'empleado_familia.tipo_documento', 'empleado_familia.num_documento', 'empleado_familia.genero',
            'empleado_familia.escolaridad', 'empleado_familia.profesion', 'empleado_familia.vivecon_el_empleado', 'empleado_familia.depende_empleado'
        ])
            ->leftjoin('empleados', 'empleados.id', 'empleado_familia.empleado_id')
            ->leftjoin('familiares', 'familiares.id', 'empleado_familia.familiar_id')
            ->leftjoin('users', 'users.cedula', 'empleados.Documento')
            ->where('empleado_id', $id)->get();
        return response()->json($familia, 200);
    }
    public function eps(Request $request)
    {
        $area = eps::select('*')->where('estado_id', 7)
            ->get();
        return response()->json($area, 200);
    }

    public function area(Request $request)
    {
        $area = Area::select('*')->where('estado_id', 7)
            ->get();
        return response()->json($area, 200);
    }

    public function store(Request $request)
    {
        $registro = Validator::make($request->all(), [
            'celular'    => 'required',
            'Documento'    => 'required|unique:empleados,Documento,id',
            'correo'    => Rule::unique('empleados', 'correo'),
            'correo_corporativo'    => Rule::unique('empleados', 'correo_corporativo'),
            'eps_id'     => 'required',
            'area_id'    => 'required',
            'tipo_documento'  => 'required|string',
            'primer_nombre'  => 'required|string',
            'segundo_nombre'  => 'required|string',
            'primer_apellido'  => 'required|string',
            'segundo_apellido'  => 'string',
            'lugar_exp_documento'  => 'required',
            'fecha_exp_documento'  => 'required',
            'genero'  => 'required',
            'grupo_sanguineo'  => 'required',
            'etnia'  => 'required',
            'fecha_nacimiento' => 'required',
            'tipo_discapacidad'  => 'string',
            'lugar_exp_documento'  => 'required',
            'lugar_nacimineto' => 'required',
            'cabeza_familia'  => 'required',
            'estado_civil'  => 'required',
            'estatura'  => 'required',
            'peso'  => 'required',
            'direccion_residencia' => 'required',
            'barrio' => 'required',
            'tipo_vivienda' => 'required',
            'estrato' => 'required',
            'municipio_id' => 'required',
            'salario' => 'required',
            'telefono' => 'required',

        ]);
        if ($registro->fails()) {
            return response()->json(["errors" => $registro->getMessageBag()], 422);
        }
        $campo = $request->primer_nombre . ' ' . $request->segundo_nombre . ' ' .
            $request->primer_apellido . ' ' . $request->segundo_apellido;

        if ($request->contacto_emergencia == null) {
            $contacto = 'Ninguno';
        } else {
            $contacto = $request->contacto_emergencia;
        }

        if ($request->tel_contacto_emergencia == null) {
            $contacto2 = 0;
        } else {
            $contacto2 = $request->tel_contacto_emergencia;
        }

        if ($request->parentesco_contacto == null) {
            $contacto1 = 'Ninguno';
        } else {
            $contacto1 = $request->parentesco_contacto;
        }

        if ($request->tipo_discapacidad == null) {
            $discapacidad = 'Ninguna';
        } else {
            $discapacidad = $request->tipo_discapacidad;
        }

        $input = $request->all();
        $input['Nombre'] = $campo;
        $input['primer_nombre'] = $request->primer_nombre;
        $input['segundo_nombre'] = $request->segundo_nombre;
        $input['primer_apellido'] = $request->primer_apellido;
        $input['segundo_apellido'] = $request->segundo_apellido;
        $input['contacto_emergencia'] = $contacto;
        $input['parentesco_contacto'] = $contacto1;
        $input['tel_contacto_emergencia'] = $contacto2;
        $input['tipo_discapacidad'] = $discapacidad;
        $input['peso'] = $request->peso . 'Kg';
        $input['estatura'] = $request->estatura . 'Cm';

        $dato = Empleado::create($input);

        // if ($request->familia == "Si") {
        //     $familia = Validator::make($request->all(), [
        //         'nombre' => 'required',
        //         'familiar_id' => 'required',
        //         'tipo_documento' => 'required',
        //         'num_documento' => Rule::unique('empleado_familia', 'correo'),
        //         'fecha_nacimiento' => 'required',
        //         'genero' => 'required',
        //         'escolaridad' => 'required',
        //         'profesion' => 'required',
        //         'depende_empleado' => 'required',
        //         'vivecon_el_empleado' => 'required',
        //         'edad' => 'required',
        //     ]);

        //     if ($familia->fails()) {
        //         return response()->json(["errors" => $familia->getMessageBag()], 422);
        //     }
        //     empleado_familia::create([
        //         'nombre' => $request->nombres,
        //         'familiar_id' => $request->familiar_id,
        //         'tipo_documento' => $request->tipo_documentos,
        //         'num_documento' => $request->num_documento,
        //         'fecha_nacimiento' => $request->fecha_nacimientos,
        //         'genero' => $request->generos,
        //         'escolaridad' => $request->escolaridad,
        //         'profesion' => $request->profesion,
        //         'depende_empleado' => $request->depende_empleado,
        //         'vivecon_el_empleado' => $request->vivecon_el_empleado,
        //         'empleado_id' => $dato['id'],
        //         'edad' => $request->edad,
        //         'nombre_documento' => 1,
        //         'created_at' => Carbon::now()
        //     ]);
        // }
        return response()->json([
            'message' => 'Empleado creado con exito!'
        ], 201);
    }

    public function familias(Request $request)
    {
        $dato = Empleado::where('Documento', $request->Documento)->first();
        empleado_familia::create([
            'nombre' => $request->nombres,
            'familiar_id' => $request->familiar_id,
            'tipo_documento' => $request->tipo_documentos,
            'num_documento' => $request->num_documento,
            'fecha_nacimiento' => $request->fecha_nacimientos,
            'genero' => $request->generos,
            'escolaridad' => $request->escolaridad,
            'profesion' => $request->profesion,
            'depende_empleado' => $request->depende_empleado,
            'vivecon_el_empleado' => $request->vivecon_el_empleado,
            'empleado_id' => $dato['id'],
            'edad' => $request->edad,
            'nombre_documento' => 1
        ]);
        return response()->json([
            'message' => 'Empleado creado con exito!'
        ], 201);
    }

    public function tipoFamilia()
    {
        $tipo = Tipofamiliar::all();
        return response()->json($tipo, 200);
    }

    public function editarempleado(Request $request, $id)
    {
        Empleado::find($id)->update($request->all());
    }

    public function editarfamiliar(Request $request, $id)
    {
        empleado_familia::find($id)->update($request->all());
    }

    public function agregar($id, Request $request)
    {
        $familia = Validator::make($request->all(), [
            'nombre' => 'required',
            'familiar_id' => 'required',
            'tipo_documento' => 'required',
            'num_documento' => Rule::unique('empleado_familia', 'num_documento'),
            'fecha_nacimiento' => 'required',
            'genero' => 'required',
            'escolaridad' => 'required',
            'profesion' => 'required',
            'depende_empleado' => 'required',
            'vivecon_el_empleado' => 'required',
            'edad' => 'required',
        ]);

        if ($familia->fails()) {
            return response()->json(["errors" => $familia->getMessageBag()], 422);
        }

        empleado_familia::insert([
            'nombre' => $request->nombre,
            'familiar_id' => $request->familiar_id,
            'tipo_documento' => $request->tipo_documento,
            'num_documento' => $request->num_documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'escolaridad' => $request->escolaridad,
            'profesion' => $request->profesion,
            'depende_empleado' => $request->depende_empleado,
            'vivecon_el_empleado' => $request->vivecon_el_empleado,
            'edad' => $request->edad,
            'empleado_id' => $id,
            'nombre_documento' => 'Ninguno',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return response()->json([
            'message' => 'Familiar Agregado con exito!'
        ], 200);
    }
    public function cambiar(Request $request, $id)
    {
        if ($request->Nombre_estado == '1') {
            Empleado::where('id', $id)->update([
                'estado_id' => 2,
                'updated_at' => Carbon::now()
            ]);
        } else if ($request->Nombre_estado == '2') {
            Empleado::where('id', $id)->update([
                'estado_id' => 1,
                'updated_at' => Carbon::now()
            ]);
        }
        return response()->json(['message' => 'hola']);
    }
}
