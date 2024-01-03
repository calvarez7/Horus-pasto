<?php

namespace App\Http\Controllers\Midiagnostico;

use App\Http\Controllers\Controller;
use App\Modelos\Midiagnostico;
use Illuminate\Http\Request;

class MidiagnosticoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $midiagnostico = Midiagnostico::updateOrCreate(
                ['cedula' => $request->cedula],
                [
                    'azucar' => $request->azucar,
                    'ciclo_vida' => $request->ciclo_vida,
                    'clasificacion' => $request->clasificacion,
                    'colesterol_hdl' => $request->colesterol_hdl,
                    'diabetes' => $request->diabetes,
                    'ejercicio' => $request->ejercicio,
                    'eres_diabetico' => $request->eres_diabetico,
                    'examen_colesterol' => $request->examen_colesterol,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'frutas_verduras' => $request->frutas_verduras,
                    'fumador' => $request->fumador,
                    'hipertension' => $request->hipertension,
                    'imc' => $request->imc,
                    'perimetro_abdominal' => $request->perimetro_abdominal,
                    'peso' => $request->peso,
                    'sexo' => $request->sexo,
                    'talla' => $request->talla,
                    'total_colesterol' => $request->total_colesterol,
                    'trigliceridos' => $request->trigliceridos,
                    'email' => $request->email,
                    'presion_sistolica' => $request->presion_sistolica,
                    'presion_diastolica' => $request->presion_diastolica
                ],
            );
            return response()->json([
                'res' => true,
                'mensaje' => 'Su solicitud se ha generado con éxito. Estamos procesando sus resultados ¡En unos segundos estará listo!',
                'data' => $midiagnostico,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'error' => $th->getMessage(),
                'mensaje' => 'Error al generar su diagnostico!'
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
