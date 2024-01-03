<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use App\Modelos\Patologia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatologiaController extends Controller
{
    public function patologias(citapaciente $citaPaciente)
    {
        $patologia = Patologia::where('Cita_id', $citaPaciente->id)->get();
        return response()->json($patologia, 201);
    }

    public function store(Request $request, citapaciente $citaPaciente)
    {
        if (!$this->isOpenCita($citaPaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }
        $patologia = Patologia::where('CitaPaciente_id', $citaPaciente->id)->first();

        if (!isset($patologia)) {
            Patologia::create([
                'CitaPaciente_id'       => $citaPaciente->id,
                'Patologiacancelactual' => $request->Patologiacancelactual,
                'fdxcanceractual'       => $request->fdxcanceractual,
                'flaboratoriopatologia' => $request->flaboratoriopatologia,
                'tumorsegunbiopsia'     => $request->tumorsegunbiopsia,
                'localizacioncancer'    => $request->localizacioncancer,
                'Dukes'                 => $request->Dukes,
                'gleason'               => $request->gleason,
                'Her2'                  => $request->Her2,
                'Estadificaciónclinica' => $request->Estadificaciónclinica,
                'estadificacioninicial' => $request->estadificacioninicial,
                'fechaestadificacion'   => $request->fechaestadificacion,
            ]);
            return response()->json(['message' => 'Patologia creada con Exito!'], 201);
        } else {
            $patologia->update([
                'Patologiacancelactual' => $request->Patologiacancelactual,
                'fdxcanceractual'       => $request->fdxcanceractual,
                'flaboratoriopatologia' => $request->flaboratoriopatologia,
                'tumorsegunbiopsia'     => $request->tumorsegunbiopsia,
                'localizacioncancer'    => $request->localizacioncancer,
                'Dukes'                 => $request->Dukes,
                'gleason'               => $request->gleason,
                'Her2'                  => $request->Her2,
                'Estadificaciónclinica' => $request->Estadificaciónclinica,
                'estadificacioninicial' => $request->estadificacioninicial,
                'fechaestadificacion'   => $request->fechaestadificacion,
            ]);
            return response()->json(['message' => 'Patologia Actializada con Exito!'], 201);
        }

    }

    public function fetchPatologia(citapaciente $citaPaciente)
    {
        $patologia = Patologia::select(
            'Patologiacancelactual',
            'fdxcanceractual',
            'flaboratoriopatologia',
            'tumorsegunbiopsia',
            'localizacioncancer',
            'Dukes',
            'gleason',
            'Her2',
            'Estadificaciónclinica',
            'estadificacioninicial',
            'fechaestadificacion'
        )
            ->where('CitaPaciente_id', $citaPaciente->id)
            ->first();
        return response()->json($patologia, 200);
    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }
}
