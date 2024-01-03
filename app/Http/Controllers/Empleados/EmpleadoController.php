<?php

namespace App\Http\Controllers\Empleados;

use App\Modelos\Pqrsf;
use App\Modelos\Empleado;
use Illuminate\Http\Request;
use App\Modelos\Empleadopqrsf;
use App\Http\Controllers\Controller;

class EmpleadoController extends Controller
{
    public function allactive()
    {
        $empleados = Empleado::getRepository()->allactive()->get();
        return response()->json($empleados, 201);
    }

    public function pqrsfEmpleados(Pqrsf $pqrsf)
    {
        $pqrsfempleados = Empleadopqrsf::select(['empleados.Nombre', 'empleados.id'])
        ->join('empleados', 'empleadospqrsf.Empleado_id', 'empleados.id')
        ->where('Pqrsf_id', $pqrsf->id)
        ->get();
        return response()->json($pqrsfempleados, 201);
    }

    /**
     * @param Request $request
     * @edit David Peláez < Agrego el parametro request y un scope de el estado >
     */
    public function getEmpleados(Request $request)
    {
        try {
            $empleados = Empleado::
                whereEstado($request->estado)
                ->select([
                    'Empleados.id',
                    'Empleados.tipo_documento',
                    'Empleados.salario',
                    'Empleados.Nombre',
                    'Empleados.Area',
                    'Empleados.Documento',
                    'Empleados.correo',
                    'Empleados.Estado_id',
                    'Empleados.celular',
                    'e.Nombre as Estado'
                ])
                ->join('Estados as e', 'Empleados.Estado_id', 'e.id')
                ->get();
            return response()->json($empleados ,201);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }

        $empleados = Empleado::select(['Empleados.id','Empleados.tipo_documento','Empleados.salario','Empleados.Nombre','Empleados.Area','Empleados.Documento', 'Empleados.correo','Empleados.Estado_id','Empleados.celular','e.Nombre as Estado'])
        ->join('Estados as e', 'Empleados.Estado_id', 'e.id')
        ->get();
        return response()->json($empleados, 201);

    }

    public function getDatosPersonales($identificador)
    {
        $empleados = Empleado::select(['Empleados.*','e.Nombre as Estado'])
        ->join('Estados as e', 'Empleados.Estado_id', 'e.id')
        ->where('Empleados.id', $identificador)
        ->first();

        return response()->json($empleados, 200);

    }


    public function store(Request $request)
    {
        $input = $request->all();
       $empleado = Empleado::create($input);
       return response()->json([
           'message' => 'Empleado creado con exito!'
       ], 201);
    }

    public function update(Request $request, Empleado $empleado)
    {
        // dd($request['Estado']);
        // $request->Estado_id = ($request['Estado'] == 'Activo'?1:2);
        $empleado->update($request->all());
        return response()->json([
            'message' => 'Empleado actualizado con exito!',
        ]);
    }
    public function fethPendientes()
    {
        $empleados = Empleado::select(['Empleados.*','e.Nombre as Estado'])
        ->join('Estados as e', 'Empleados.Estado_id', 'e.id')
        ->where('e.id',5)
        ->get();

        return response()->json($empleados, 200);
    }
    public function pendientes()
    {
        $examenesPendientes = Empleado::select(['Empleados.*'])
        ->join('Estados as e', 'Empleados.Estado_id', 'e.id')
        ->join('jefe_inmediatos as j','j.empleado_id','Empleados.id')
        ->where('e.id',47)
        ->where('j.jefe_id',auth()->user()->id)
        ->get();

        return response()->json($examenesPendientes, 200);
    }

    
}
