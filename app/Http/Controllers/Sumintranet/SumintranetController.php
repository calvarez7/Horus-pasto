<?php

namespace App\Http\Controllers\Sumintranet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modelos\Empleado;
use App\Modelos\Municipio;
use Illuminate\Cache\RetrievesMultipleKeys;

class SumintranetController extends Controller
{
    public function empleados()
    {
        $empleados = Empleado::all();
        return response()->json($empleados, 200);
    }

    public function municipios()
    {
        $municipios = Municipio::all(['id','Nombre']);
        return response()->json($municipios, 200);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        $documento = Empleado::where('Documento', $request->Documento)->first();
        if ($documento) {
            return 'La cedula Si existe';
            // $documento->update($request->all());
            // return response()->json([
            //     'message' => 'empleado Actualizado con exito'
            // ]);
        }else {
            return 'La cedula no existe';
            // $documento = new Empleado;
            // $documento->Nombre = $request->primer_nombre. ' '. $request->segundo_nombre. ' '. $request->primer_apellido. ' '. $request->segundo_apellido;
            // $documento->Documenton = $request->Documenton;
            // $documento->Nombre = $request->Nombre;
            // $documento->area_id = $request->area_id;
            // $documento->barrio = $request->barrio;
            // $documento->cabeza_familia = $request->cabeza_familia;
            // $documento->celular = $request->celular;
            // $documento->celular2 = $request->celular2;
            // $documento->contacto_emergencia = $request->contacto_emergencia;
            // $documento->correo = $request->correo;
            // $documento->correo_corporativo = $request->correo_corporativo;
            // $documento->depende_empleado = $request->depende_empleado;
            // $documento->direccion_residencia = $request->direccion_residencia;
            // $documento->discapacidad = $request->discapacidad;
            // $documento->edad = $request->edad;
            // $documento->empleado_id = $request->empleado_id;
            // $documento->eps_id = $request->eps_id;
            // $documento->escolaridad = $request->escolaridad;
            // $documento->estado_civil = $request->estado_civil;
            // $documento->estatura = $request->estatura;
            // $documento->estrato = $request->estrato;
            // $documento->etnia = $request->etnia;
            // $documento->familiar_id = $request->familiar_id;
            // $documento->fecha_exp_documento = $request->fecha_exp_documento;
            // $documento->fecha_nacimiento = $request->fecha_nacimiento;
            // $documento->genero = $request->genero;
            // $documento->grupo_sanguineo = $request->grupo_sanguineo;
            // $documento->lugar_exp_documento = $request->lugar_exp_documento;
            // $documento->lugar_nacimiento = $request->lugar_nacimiento;
            // $documento->municipio_id = $request->municipio_id;
            // $documento->nombre = $request->nombre;
            // $documento->nombre_documento = $request->nombre_documento;
            // $documento->nombre_foto = $request->nombre_foto;
            // $documento->num_documento = $request->num_documento;
            // $documento->parentesco_contacto = $request->parentesco_contacto;
            // $documento->peso = $request->peso;
            // $documento->poblacion_especial = $request->poblacion_especial;
            // $documento->primer_apellido = $request->primer_apellido;
            // $documento->primer_nombre = $request->primer_nombre;
            // $documento->profesion = $request->profesion;
            // $documento->ruta_documento = $request->ruta_documento;
            // $documento->ruta_foto = $request->ruta_foto;
            // $documento->segundo_apellido = $request->segundo_apellido;
            // $documento->segundo_nombre = $request->segundo_nombre;
            // $documento->tel_contacto_emergencia = $request->tel_contacto_emergencia;
            // $documento->telefono = $request->telefono;
            // $documento->tipo_discapacidad = $request->tipo_discapacidad;
            // $documento->tipo_documento = $request->tipo_documento;
            // $documento->tipo_vivienda = $request->tipo_vivienda;
            // $documento->vivecon_el_empleado = $request->vivecon_el_empleado;

            return response()->json([
                'message' => 'empleado creado exito'
            ]);

        }
    }

    public function show(Empleado $empleado)
    {
        //
    }

    public function update(Request $request, Empleado $empleado)
    {
        //
    }

    public function destroy(Empleado $empleado)
    {
        //
    }
}
