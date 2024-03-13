<?php

namespace App\Http\Controllers\Permisos;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermisoController extends Controller
{
    public function all()
    {
        $permissions = Permission::all();
        return response()->json($permissions, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input      = $request->all();
        $permission = Permission::create($input);

        return response()->json([
            'message' => 'Permiso creado con Exito!'], 201);
    }

    public function show(Permission $permission)
    {
        return response()->json($permission, 200);
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->all());
        return response()->json([
            'message' => 'Permiso Actualizado con Exito!',
        ], 200);
    }

    public function destroy(Permission $permission)
    {
        DB::table("permissions")->where('id', $permission)->delete();
        return response()->json([
            'message' => 'Permiso Eliminado con Exito!'], 200);
    }

    public function permissionpqrsf()
    {
        $permipqrsf = Permission::where('Tipo_id', 7)->get();
        return response()->json($permipqrsf, 200);
    }

    public function permissionuser(User $user)
    {   
        $permissions = $user->getAllPermissions();
        foreach ($permissions as $permiso) {
            $permisoTipo = Permission::select('tipos.Nombre')
            ->join('tipos', 'Tipo_id', 'tipos.id')
            ->where('permissions.id', $permiso->id)
            ->first();
            if ($permisoTipo) {
                $permisoString = (string) $permisoTipo['Nombre'];
                $permiso['Tipo'] = $permisoString;
            } else {
                $permiso['Tipo'] = null;
            }
        }
        return response()->json($permissions, 200);
    }

    public function allWithTipos()
    {
        $permissions = Permission::select('tipos.Nombre', 'permissions.name', 'permissions.id')
        ->leftjoin('tipos', 'Tipo_id', 'tipos.id')
        ->get();
        return response()->json($permissions, 200);
    }
}
