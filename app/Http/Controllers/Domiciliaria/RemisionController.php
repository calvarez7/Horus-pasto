<?php

namespace App\Http\Controllers\Domiciliaria;

use App\Modelos\Paciente;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\medicinaencasa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modelos\Departamento;

class RemisionController extends Controller
{
    public function departamento()
    {
        $departamento = Departamento::select(['Departamentos.Nombre as departamento', 'Departamentos.id as id_departamento','m.Nombre as municipio'])
                        ->join('Municipios as m', 'm.Departamento_id', 'Departamentos.id')
                        ->get();
                        return response()->json($departamento, 201);
    }

    public function remisionPendiente()
    {
        $remisiones = medicinaencasa::select(
            DB::raw("CONCAT(b.Segundo_Ape,' ',b.Primer_Ape,' ',b.Primer_Nom,' ',b.SegundoNom) as paciente"),
            'medicinaencasas.citaPaciente_id',
            'medicinaencasas.remite',
            'medicinaencasas.telIPSremite',
            'medicinaencasas.emailmedico',
            'medicinaencasas.nombrecuidador',
            'medicinaencasas.celularcuidador',
            'medicinaencasas.responsablepaciente',
            'medicinaencasas.celularresponsable',
            'medicinaencasas.indicebarthel',
            'medicinaencasas.presenciaconfusion',
            'medicinaencasas.puntuacionagudo',
            'medicinaencasas.estrategiacovi',
            'medicinaencasas.voluntariamente',
            'b.Tipo_Afiliado',
            'b.Estado_Afiliado',
            'c.Nombre',
        )
        ->join('cita_paciente as a', 'medicinaencasas.citaPaciente_id', 'a.id')
        ->join('pacientes as b', 'a.Paciente_id', 'b.id')
        ->Leftjoin('sedeproveedores as c', 'b.IPS', 'c.id')
        ->get();
        return response()->json($remisiones, 201);
    }

    public function createRemesion(Request $request, Paciente $paciente)
    {
        // $message = 'EL paciente ya cuenta con un proceso de medicina domiciliaria';
        // $getPaciente = medicinaencasa::where()
        $citapaciente               = new citapaciente();
        $citapaciente->Tipocita_id  = 9;
        $citapaciente->Paciente_id  = $paciente->id;
        $citapaciente->save();

        $medicinaencasa = new medicinaencasa();
        $medicinaencasa->user_id = auth()->user()->id;
        $medicinaencasa->citaPaciente_id = $citapaciente->id;
        $medicinaencasa->remite = $request->insRemite;
        $medicinaencasa->telIPSremite = $request->telIPS;
        $medicinaencasa->emailmedico = $request->medRemite;
        $medicinaencasa->nombrecuidador = $request->nomCuidador;
        $medicinaencasa->celularcuidador = $request->celCuidador;
        $medicinaencasa->responsablepaciente = $request->nomResponsable;
        $medicinaencasa->celularresponsable = $request->calResponsable;
        $medicinaencasa->save();
        return response()->json($citapaciente, 201);
    }

    public function update(Request $request, medicinaencasa $medicinaencasa)
    {
        //
    }
}
