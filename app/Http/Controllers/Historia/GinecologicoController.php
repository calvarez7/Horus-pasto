<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use App\Modelos\Ginecologico;
use Illuminate\Http\Request;

class GinecologicoController extends Controller
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

    public function store(Request $request, citapaciente $citapaciente)
    {

        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

        $citapacienteid = citapaciente::select('id')
        ->where('id', $citapaciente->id)
        ->first();

        $ginecologico = Ginecologico::where('ginecologicos.Citapaciente_id', $citapaciente->id)->first();

        if (!isset($ginecologico)) {
            Ginecologico::create([
                'Citapaciente_id'            => $citapaciente->id,
                'Fechaultimamenstruacion'    => $request->Fechaultimamenstruacion,
                'Gestaciones'                => $request->Gestaciones,
                'Partos'                     => $request->Partos,
                'Abortoprovocado'            => $request->Abortoprovocado,
                'Abortoespontaneo'           => $request->Abortoespontaneo,
                'Mortinato'                  => $request->Mortinato,
                'Gestantefechaparto'         => $request->Gestantefechaparto,
                'Gestantenumeroctrlprenatal' => $request->Gestantenumeroctrlprenatal,
                'Eutoxico'                   => $request->Eutoxico,
                'Cesaria'                    => $request->Cesaria,
                'Cancercuellouterino'        => $request->Cancercuellouterino,
                'Ultimacitologia'            => $request->Ultimacitologia,
                'Resultado'                  => $request->Resultado,
                'Menarquia'                  => $request->Menarquia,
                'Ciclos'                     => $request->Ciclos,
                'Regulares'                  => $request->Regulares,
                'Observaciongineco'          => $request->Observaciongineco,
                'checkboxFechaultimamenstruacion' => $request->checkboxFechaultimamenstruacion,
                'checkboxGestaciones' => $request->checkboxGestaciones,
                'checkboxPartos' => $request->checkboxPartos,
                'checkboxAbortoprovocado' => $request->checkboxAbortoprovocado,
                'checkboxAbortoespontaneo' => $request->checkboxAbortoespontaneo,
                'checkboxMortinato' => $request->checkboxMortinato,
                'checkboxGestante' => $request->checkboxGestante,
                'checkboxEutoxico' => $request->checkboxEutoxico,
                'checkboxCesaria' => $request->checkboxCesaria,
                'checkboxCancercuellouterino' => $request->checkboxCancercuellouterino,
                'checkboxCitologia' => $request->checkboxCitologia,
                'checkboxMenarquia' => $request->checkboxMenarquia,
                'checkboxCiclos' => $request->checkboxCiclos,
                'checkboxRegulares' => $request->checkboxRegulares,
            ]);
            return response()->json(['message' => 'Gineco Obstétricos guardado con exito!'], 201);
        } else {
            $ginecologico->update([
                'Citapaciente_id'            => $citapaciente->id,
                'Fechaultimamenstruacion'    => $request->Fechaultimamenstruacion,
                'Gestaciones'                => $request->Gestaciones,
                'Partos'                     => $request->Partos,
                'Abortoprovocado'            => $request->Abortoprovocado,
                'Abortoespontaneo'           => $request->Abortoespontaneo,
                'Mortinato'                  => $request->Mortinato,
                'Gestantefechaparto'         => $request->Gestantefechaparto,
                'Gestantenumeroctrlprenatal' => $request->Gestantenumeroctrlprenatal,
                'Eutoxico'                   => $request->Eutoxico,
                'Cesaria'                    => $request->Cesaria,
                'Cancercuellouterino'        => $request->Cancercuellouterino,
                'Ultimacitologia'            => $request->Ultimacitologia,
                'Resultado'                  => $request->Resultado,
                'Menarquia'                  => $request->Menarquia,
                'Ciclos'                     => $request->Ciclos,
                'Regulares'                  => $request->Regulares,
                'Observaciongineco'          => $request->Observaciongineco,
                'checkboxFechaultimamenstruacion' => $request->checkboxFechaultimamenstruacion,
                'checkboxGestaciones' => $request->checkboxGestaciones,
                'checkboxPartos' => $request->checkboxPartos,
                'checkboxAbortoprovocado' => $request->checkboxAbortoprovocado,
                'checkboxAbortoespontaneo' => $request->checkboxAbortoespontaneo,
                'checkboxMortinato' => $request->checkboxMortinato,
                'checkboxGestante' => $request->checkboxGestante,
                'checkboxEutoxico' => $request->checkboxEutoxico,
                'checkboxCesaria' => $request->checkboxCesaria,
                'checkboxCancercuellouterino' => $request->checkboxCancercuellouterino,
                'checkboxCitologia' => $request->checkboxCitologia,
                'checkboxMenarquia' => $request->checkboxMenarquia,
                'checkboxCiclos' => $request->checkboxCiclos,
                'checkboxRegulares' => $request->checkboxRegulares,
            ]);
            return response()->json(['message' => 'Gineco Obstétricos actualizado con exito!'], 201);
        }
    }

    public function getGineco(citapaciente $citapaciente)
    {

        $ginecologico = Ginecologico::select(
            'Fechaultimamenstruacion',
            'Gestaciones',
            'Partos',
            'Abortoprovocado',
            'Abortoespontaneo',
            'Mortinato',
            'Gestantefechaparto',
            'Gestantenumeroctrlprenatal',
            'Eutoxico',
            'Cesaria',
            'Cancercuellouterino',
            'Ultimacitologia',
            'Resultado',
            'Menarquia',
            'Ciclos',
            'Regulares',
            'Observaciongineco',
            'checkboxFechaultimamenstruacion',
            'checkboxGestaciones',
            'checkboxPartos',
            'checkboxAbortoprovocado',
            'checkboxAbortoespontaneo',
            'checkboxMortinato',
            'checkboxGestante',
            'checkboxEutoxico',
            'checkboxCesaria',
            'checkboxCancercuellouterino',
            'checkboxCitologia',
            'checkboxMenarquia',
            'checkboxCiclos',
            'checkboxRegulares',
            )
            ->where('Citapaciente_id', $citapaciente->id)
            ->first();

        return response()->json($ginecologico, 200);

    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }
}
