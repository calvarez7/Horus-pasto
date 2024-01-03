<?php

namespace App\Http\Controllers\Historia;

use App\User;
use App\Modelos\agendauser;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Imagenologia;
use App\Http\Controllers\Controller;

class ImagenologiaController extends Controller
{
    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }

    public function imagenologia(citapaciente $citapaciente)
    {
        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }
        $imagenologia = Imagenologia::select([
            'Indicacion as indicacion',
            'Hallazgos as hallazgos',
            'Conclusion as conclusion',
            'Notaclaratoria as notaclaratoria',
            'Cie10_id',
            'Tecnica as tecnica',
            'prioridad'
        ])
            ->where('Citapaciente_id', $citapaciente->id)
            ->first();

        return response()->json([$imagenologia], 200);
    }

    public function store(Request $request, citapaciente $citapaciente)
    {
        if (!$this->isOpencita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        // $citapacienteid = citapaciente::select('id')
        //     ->where('id', $citapaciente->id)
        //     ->first();
        $imagenologia = Imagenologia::where('Imagenologias.citapaciente_id', $citapaciente->id)
            ->first();
        if (!isset($imagenologia)) {
            Imagenologia::create([
                // 'Citapaciente_id' => $citapacienteid->id,
                'Citapaciente_id' => $citapaciente->id,
                'Indicacion'      => $request->indicacion,
                'Hallazgos'       => $request->hallazgos,
                'Conclusion'      => $request->conclusion,
                'Notaclaratoria'  => $request->notaclaratoria,
                'Tecnica'         => $request->tecnica,
                'prioridad'       => $request->prioridad,
                'medico_imagenologia' => auth()->id()
                // 'Cie10_id' => $request->Cie10_id,
            ]);
            return response()->json([
                'message' => 'Historia creada con exito',
            ]);
        } else {
            $imagenologia->update([
                'Indicacion'     => $request->indicacion,
                'Hallazgos'      => $request->hallazgos,
                'Conclusion'     => $request->conclusion,
                'Notaclaratoria' => $request->notaclaratoria,
                'Tecnica'        => $request->tecnica,
                'prioridad'      => $request->prioridad,
                'medico_imagenologia' => auth()->id()
                // 'Cie10_id' => $request->Cie10_id,
            ]);
            return response()->json([
                'message' => 'Historia actualizada con exito',
            ]);
        }
    }

    public function radiologos()
    {
        $radiologos = User::select(['users.*', 'r.*'])
        ->join('model_has_roles as r', 'users.id', 'r.model_id')
        ->where('r.role_id', 80)->get();
        return response()->json($radiologos, 200);
    }

}
