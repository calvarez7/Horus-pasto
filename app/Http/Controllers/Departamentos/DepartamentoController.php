<?php

namespace App\Http\Controllers\Departamentos;

use App\Http\Controllers\Controller;
use App\Modelos\Departamento;

class DepartamentoController extends Controller
{
    public function all()
    {
        $departamentos = Departamento::all();
        return response()->json($departamentos, 200);
    }
}
