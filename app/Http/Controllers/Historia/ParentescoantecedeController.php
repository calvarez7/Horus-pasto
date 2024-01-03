<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\Antecedentesparentesco;
use App\Modelos\citapaciente;
use App\Modelos\Parentescoantecedente;
use Illuminate\Http\Request;

class ParentescoantecedeController extends Controller
{
    public function all()
    {
        $Parentescoantecedente = Parentescoantecedente::all();
        return response()->json($Parentescoantecedente, 200);
    }

    public function store(Request $request)
    {
        Parentescoantecedente::create([
            'Antecedente_id'  => $request->antecedente,
            'Citapaciente_id' => $request->cita_paciente,
            'Descripcion'     => $request->descripcion,
            'parentesco_id'   => $request->familiar,
            'Usuario_id'      => auth()->user()->id,
            'cie10_id'     => $request->cie10_id,
        ]);
        return response()->json([
            'message' => 'Antecedentes Familiar guardado con exito!',
        ], 201);
    }
    public function familiares($cita_paciente_id)
    {
        $citaPaciente = citapaciente::find($cita_paciente_id);
        $parentescoantecedente = Parentescoantecedente::select('a.Nombre', 'Parentescoantecedentes.created_at',
                                                                'Parentescoantecedentes.Descripcion', 'u.name',
                                                                 'p.Nombre as familiar', 'c.Descripcion as cie')
            ->join('cita_paciente', 'Parentescoantecedentes.citapaciente_id', 'cita_paciente.id')
            ->leftjoin('antecedentes as a', 'Parentescoantecedentes.Antecedente_id', 'a.id')
            ->leftjoin('cie10s as c', 'Parentescoantecedentes.cie10_id', 'c.id')
            ->join('users as u', 'Parentescoantecedentes.Usuario_id', 'u.id')
            ->join('parentescos as p', 'Parentescoantecedentes.parentesco_id', 'p.id')
            ->where('cita_paciente.Paciente_id', $citaPaciente->Paciente_id)
            ->orderBy('Parentescoantecedentes.created_at', 'desc')
            ->get();
        return response()->json($parentescoantecedente, 200);
    }

}
