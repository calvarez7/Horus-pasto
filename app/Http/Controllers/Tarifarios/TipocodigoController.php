<?php

namespace App\Http\Controllers\Tarifarios;

use App\Http\Controllers\Controller;
use App\Modelos\Tipocodigo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipocodigoController extends Controller
{
    public function all()
    {
        $tipocodigo = Tipocodigo::where('Estado_id', 1)->get();
        return response()->json($tipocodigo, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'      => 'required|string',
            'Descripcion' => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input = $request->all();
        Tipocodigo::create($input);
        return response()->json([
            'message' => 'Tipocodigo creado con Exito!',
        ], 201);
    }

    public function update(Request $request, Tipocodigo $tipocodigo)
    {
        $tipocodigo->update($request->all());
        return response()->json([
            'message' => 'Tipocodigo actualizado con Exito!',
        ], 200);
    }

    public function getCodeTypeByRole()
    {
        $rolesUser = auth()->user()->getRoleNames()->toArray();
        $access    = $this->getAccess($rolesUser);
        $codeType  = [];

        if (!empty($access)) {

            $codeType = Tipocodigo::select('*')
                ->where(function ($query) use ($access) {
                    foreach ($access as $row) {
                        $query->orWhere('Nombre', $row);
                    }
                })
                ->get();

        }

        return response()->json($codeType, 201);

    }

    private function getAccess($rolesUser)
    {
        $access = [
            'Edit Medicamentos',
            'Edit Insumos',
            'Edit Servicios',
        ];

        $array = [];

        foreach ($access as $row) {
            if (in_array($row, $rolesUser)) {
                $codeType = explode(' ', $row);
                array_push($array, $codeType[1]);
            }
        }

        return $array;
    }

}
