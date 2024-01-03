<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\Antecedente;
use App\Modelos\NotaAclaratoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AntecedenteController extends Controller
{
    public function all()
    {
        $antecedentes = Antecedente::all();
        return response()->json($antecedentes, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre' => 'string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input       = $request->all();
        $antecedente = Antecedente::create($input);
        return response()->json([
            'message' => 'Antecedente creado con exito!',
        ], 201);
    }

    public function update(Request $request, Antecedente $antecedente)
    {
       $antecedente->update($request->all());
        return response()->json([
            'message' => 'Antecedente actualizado con exito!',
        ], 200);
    }
    public function notaAclaratoria(Request $request){

        NotaAclaratoria::create([
            'Citapaciente_id' => $request->id,
            'nota'    => $request->aclaratoria,
            'users_id'      => auth()->id()]);

        return response()->json([
            'message' => 'informacion guardad con exito'
        ], 200);

    }
    public function revisarNota($citaPacienteid) {
        $nota=NotaAclaratoria::select('nota_aclaratorias.*','users.name','users.apellido')
        ->join('users','users.id','nota_aclaratorias.users_id')
        ->where('Citapaciente_id',$citaPacienteid)->get();
        return response()->json($nota, 200);
    }
}
