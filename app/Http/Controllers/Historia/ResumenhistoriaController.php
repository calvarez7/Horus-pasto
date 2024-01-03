<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumenhistoriaController extends Controller
{

    public function getPhysicalExam(citapaciente $patientAppointment)
    {
        $physicalExam = $patientAppointment->examenfisico;

        return response()->json($physicalExam, 200);
    }

    public function gethistoria(Request $request)
    {
        $historia = DB::select("exec dbo.historialHC" . "'$request->Num_Doc'");
        return response()->json($historia, 200);
    }
    public function historiaid(Request $request)
    {
        $historia = DB::select("exec dbo.HistoriaHC_id" . "'$request->referenciaid'");
        return response()->json($historia, 200);
    }

    public function getdomiciliaria(Request $request)
    {
        $historia = DB::select("exec dbo.Domiciliaria" . "'$request->Num_Doc'");
        return response()->json($historia, 200);
    }

    public function getlaboratorios(Request $request)
    {
        $laboratorios = DB::select("exec dbo.laboratorios" . "'$request->Num_Doc'");
        return response()->json($laboratorios, 200);
    }

    public function getResultadolaboratorios(Request $request)
    {
        $resultados = DB::select("exec dbo.Resultadolaboratorios" . "'$request->Num_orden'");
        return response()->json($resultados, 200);
    }

    public function gethistoriaradium(Request $request)
    {
        $historiaradium = Collect(DB::select("exec dbo.GeneraHC_Imagenologia ?,?,?",
        [ $request->Num_Doc ,$request->Finicio, $request->Ffinal]));

        return response()->json($historiaradium, 200);
    }

    public function getHistoryByCita($citaPaciente){
        $historia = DB::select("exec dbo.Genera_HC_Oncologia " . $citaPaciente);
        return response()->json($historia, 200);
    }
}
