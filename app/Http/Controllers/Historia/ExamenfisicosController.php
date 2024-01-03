<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use App\Modelos\Examenfisico;
use App\Modelos\notaenfermeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamenfisicosController extends Controller
{
    public function all()
    {
        $examenfisicos = Examenfisico::all();
        return response()->json($examenfisicos, 200);
    }

    public function antropometricas(Request $request, citapaciente $citapaciente)
    {
        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        $validate = Validator::make($request->all(), [
            'Talla' => 'string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $citapacienteid = citapaciente::select('id')
            ->where('id', $citapaciente->id)
            ->first();

        $examenfis = Examenfisico::where('Examenfisicos.citapaciente_id', $citapaciente->id)
            ->first();

        if (!isset($examenfis)) {
            Examenfisico::create([
                'Citapaciente_id'    => $citapacienteid->id,
                'Peso'               => $request->Peso,
                'Talla'              => $request->Talla,
                'Imc'                => $request->Imc,
                'ISC'               => $request->ISC,
                'Clasificacion'      => $request->Clasificacion,
                'Perimetroabdominal' => $request->Perimetroabdominal,
                'Perimetrocefalico'  => $request->Perimetrocefalico,
            ]);
            return response()->json(['message' => 'Medidas antropometricas guardadas con exito!'], 201);
        } else {
            $examenfis->update([
                'Peso'               => $request->Peso,
                'Talla'              => $request->Talla,
                'Imc'                => $request->Imc,
                'ISC'               => $request->ISC,
                'Clasificacion'      => $request->Clasificacion,
                'Perimetroabdominal' => $request->Perimetroabdominal,
                'Perimetrocefalico'  => $request->Perimetrocefalico,
            ]);
            return response()->json(['message' => 'Medidas antropometricas actualizadas con exitosamente!'], 201);
        }

    }

    public function signosvitales(Request $request, citapaciente $citapaciente)
    {

        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        // $validate = Validator::make($request->all(),[
        //     'Temperatura' => 'string',
        //     'Frecurespiratoria' => 'string',
        //     'Frecucardiaca' => 'string',
        // ]);
        // if ($validate->fails()) {
        //     $errores = $validate->errors();
        //     return response()->json($errores, 422);
        // }
        $examenfisicoid = Examenfisico::where('Citapaciente_id', $citapaciente->id)
            ->first();
        // return $examenfisicoid->Citapaciente_id;
        if (isset($examenfisicoid->Citapaciente_id)) {
            $examenfisicoid->update([
                'Frecucardiaca'        => $request->Frecucardiaca,
                'Pulsos'               => $request->Pulsos,
                'Frecurespiratoria'    => $request->Frecurespiratoria,
                'Temperatura'          => $request->Temperatura,
                'Saturacionoxigeno'    => $request->Saturacionoxigeno,
                'Posicion'             => $request->Posicion,
                'Lateralidad'          => $request->Lateralidad,
                'Presionsistolica'     => $request->Presionsistolica,
                'Presiondiastolica'    => $request->Presiondiastolica,
                'Presionarterialmedia' => $request->Presionarterialmedia,
            ]);
        }
        return response()->json(['message' => 'Signos vitales guardados con exito!'], 201);
    }

    public function examenfisico(Request $request, citapaciente $citapaciente)
    {

        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        $examenfisicoid = Examenfisico::where('Citapaciente_id', $citapaciente->id)
            ->first();
        if (isset($examenfisicoid->Citapaciente_id)) {
            $examenfisicoid->update([
                'Condiciongeneral'      => $request->Condiciongeneral,
                'Cabezacuello'          => $request->Cabezacuello,
                'Ojosfondojos'          => $request->Ojosfondojos,
                'Agudezavisual'         => $request->Agudezavisual,
                'Cardiopulmonar'        => $request->Cardiopulmonar,
                'Abdomen'               => $request->Abdomen,
                'Osteomuscular'         => $request->Osteomuscular,
                'Extremidades'          => $request->Extremidades,
                'Pulsosperifericos'     => $request->Pulsosperifericos,
                'Neurologico'           => $request->Neurologico,
                'Reflejososteotendinos' => $request->Reflejososteotendinos,
                'Pielfraneras'          => $request->Pielfraneras,
                'Genitourinario'        => $request->Genitourinario,
                'Examenmama'            => $request->Examenmama,
                'Tactoretal'            => $request->Tactoretal,
                'Examenmental'          => $request->Examenmental,

            ]);
        }
        return response()->json(['message' => 'Examenfisico guardados con exito!'], 201);
    }

    public function getExamenFisico(citapaciente $citapaciente)
    {
        $data = [];

        $examenfisico = Examenfisico::select(
            'Peso',
            'Talla',
            'Imc',
            'ISC',
            'Clasificacion',
            'Perimetroabdominal',
            'Perimetrocefalico',
            'Frecucardiaca',
            'Pulsos',
            'Frecurespiratoria',
            'Temperatura',
            'Saturacionoxigeno',
            'Posicion',
            'Lateralidad',
            'Presionsistolica',
            'Presiondiastolica',
            'Presionarterialmedia',
            'Condiciongeneral',
            'Cabezacuello',
            'Ojosfondojos',
            'Agudezavisual',
            'Cardiopulmonar',
            'Abdomen',
            'Osteomuscular',
            'Extremidades',
            'Pulsosperifericos',
            'Neurologico',
            'Reflejososteotendinos',
            'Pielfraneras',
            'Genitourinario',
            'Examenmama',
            'Tactoretal',
            'Examenmental')
            ->where('Citapaciente_id', $citapaciente->id)
            ->first();
         $data["examen"] = $examenfisico;

         $data["nota"] = notaenfermeria::where('cita_paciente_id',$citapaciente->id)->first();

        return response()->json($data, 200);

    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }

}
