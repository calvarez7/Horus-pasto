<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use App\Modelos\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{

    public function all()
    {
        $marca = Marca::select('Pacientes.Primer_Nom', 'Estados.Nombre')
            ->join('Pacientes', 'Marcas.Paciente_id', 'Pacientes.id')
            ->join('Estados', 'Marcas.Estado_id', 'Estados.id')
            ->get();

        return response()->json($marca, 200);
    }

    public function store(Request $request, citapaciente $citapaciente)
    {
        //
    }

}
