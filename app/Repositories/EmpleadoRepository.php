<?php
namespace App\Repositories;

use App\Modelos\Empleado;
use Illuminate\Support\Facades\DB;

class EmpleadoRepository
{
    public function allactive(){ 

        return $empleados = Empleado::select([
            'empleados.id', 'empleados.Nombre', 'Estados.Nombre as Estado', 'empleados.Documento',
            'empleados.Area', 'empleados.correo', 'empleados.celular'
        ])
        ->where('Estados.id', 1)
        ->join('Estados', 'empleados.Estado_id', 'Estados.id');
    }
}
