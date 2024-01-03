<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use App\Modelos\Areahelp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreahelpController extends Controller
{
    public function getarea()
    {
        $areahelp = Areahelp::select('areas_help.id as id', 'areas_help.Nombre as Nombre', 'areas_help.Descripcion',
            'permissions.name as NombrePermiso', 'estados.Nombre as Estado')
            ->join('permissions', 'areas_help.Permission_id', 'permissions.id')
            ->join('estados', 'areas_help.Estado_id', 'estados.id')
            ->get();
        return response()->json($areahelp, 201);
    }

    public function getAreaEnable()
    {
        $areahelp = Areahelp::select('areas_help.id as id', 'areas_help.Nombre as Nombre', 'areas_help.Descripcion',
            'permissions.name as NombrePermiso', 'estados.Nombre as Estado')
            ->join('permissions', 'areas_help.Permission_id', 'permissions.id')
            ->join('estados', 'areas_help.Estado_id', 'estados.id')
            ->where('Estado_id', 1)
            ->get();
        return response()->json($areahelp, 201);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'        => 'required|string|unique:areas_help',
            'Descripcion'   => 'required|string',
            'Permission_id' => 'required',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $input = $request->all();
        $area  = Areahelp::create($input);

        return response()->json([
            'message' => 'Área creada con exito!',
        ], 201);
    }

    public function enable($areahelp)
    {
        Areahelp::where('id', $areahelp)
            ->update([
                'Estado_id' => 1,
            ]);

        return response()->json([
            'message' => '¡Requerimiento Habilitado!',
        ], 200);
    }

    public function disable($areahelp)
    {
        Areahelp::where('id', $areahelp)
            ->update([
                'Estado_id' => 2,
            ]);

        return response()->json([
            'message' => '¡Requerimiento Deshabilitado!',
        ], 200);
    }

    public function updatearea(Request $request, Areahelp $areahelp)
    {
        if ($areahelp->Nombre !== $request->Nombre) {
            $validate = Validator::make($request->all(), [
                'Nombre' => 'unique:areas_help',
            ]);
            if ($validate->fails()) {
                $errores = $validate->errors();
                return response()->json($errores, 422);
            }
        }

        $areahelp->Nombre      = $request->Nombre;
        $areahelp->Descripcion = $request->Descripcion;
        if (isset($request->Permission_id)) {
            $areahelp->Permission_id = $request->Permission_id;
        }
        $areahelp->save();

        return response()->json([
            'message' => 'Área actualizada con Exito!',
        ], 200);
    }
}
