<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use App\Modelos\Conducta;
use App\Modelos\Saludocupacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConductaController extends Controller
{

    public function all()
    {
        $conducta = Conducta::all();
        return response()->json($conducta, 200);
    }

    public function fin(Request $request, citapaciente $citapaciente)
    {
        // return $request->all();
        // if (!$this->isOpenCita($citapaciente->Estado_id)) {
        //     return response()->json([
        //         'message' => '¡La historia del paciente no se encuentra activa!',
        //     ], 422);
        // }

        $validate = Validator::make($request->all(), [
            'Planmanejo'      => 'required|string',
            'Recomendaciones' => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $conduc = Conducta::where('Conductas.citapaciente_id', $citapaciente->id)
            ->first();
            $citapaciente->update(['Finalidad' => $request->Finalidad]);

        // $citapacienteid = citapaciente::where('id', $citapaciente->id)->first();
        if (!isset($conduc)) {
            Conducta::create([
                'Citapaciente_id' => $citapaciente->id,
                'Planmanejo'      => $request->Planmanejo,
                'Recomendaciones' => $request->Recomendaciones,
                'Destinopaciente' => $request->Destinopaciente,
                'Finalidad'       => $request->Finalidad,
                'Especialidad' => $request->Especialidad_remi,
            ]);
            $saludocupacional = Saludocupacional::where('cita_paciente_id', $citapaciente->id)->first();
            $saludocupacional->update(['aptitud_laboral_psicologia'=>$request->aptitud_laboral_psicologia,
            'aptitud_laboral_medico'=>$request->aptitud_laboral_medico,
            'aptitud_laboral_visiomretria'=>$request->aptitud_laboral_visiomretria,
            'aptitud_laboral_voz'=> $request->aptitud_laboral_voz,
            'vigilancia_epidemiologico'=>$request->vigilancia_epidemiologico]);
            return response()->json(['message' => 'La consulta finaliza exitosamente!'], 201);
        } else {
            $conduc->update([
                'Planmanejo'      => $request->Planmanejo,
                'Recomendaciones' => $request->Recomendaciones,
                'Destinopaciente' => $request->Destinopaciente,
                'Finalidad'       => $request->Finalidad,
            ]);
            $saludocupacional = Saludocupacional::where('cita_paciente_id', $citapaciente->id)->first();
            $saludocupacional->update(['aptitud_laboral_psicologia'=>$request->aptitud_laboral_psicologia,
            'aptitud_laboral_medico'=>$request->aptitud_laboral_medico,
            'aptitud_laboral_visiomretria'=>$request->aptitud_laboral_visiomretria,
            'aptitud_laboral_voz'=> $request->aptitud_laboral_voz,
            'vigilancia_epidemiologico'=>$request->vigilancia_epidemiologico]);
            return response()->json(['message' => 'Conducta actualizada exitosamente!'], 201);
        }
    }

    public function getConductaByCita(citapaciente $citapaciente)
    {
        $citapaciente = Conducta::select(
            'conductas.Planmanejo',
            'conductas.Recomendaciones',
            'conductas.Destinopaciente',
            'conductas.Finalidad',
            'saludocupacionals.aptitud_laboral_psicologia',
            'saludocupacionals.aptitud_laboral_medico',
            'saludocupacionals.vigilancia_epidemiologico',
            'saludocupacionals.aptitud_laboral_visiomretria',
            'saludocupacionals.aptitud_laboral_voz'
        )
            ->leftJoin('saludocupacionals','saludocupacionals.cita_paciente_id','conductas.Citapaciente_id')
            ->where('conductas.Citapaciente_id', $citapaciente->id)
            ->first();
        return response()->json($citapaciente, 200);
    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }

}
