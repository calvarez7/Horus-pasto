<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\Auditoria;
use App\Modelos\Incapacidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;
use Rap2hpoutre\FastExcel\FastExcel;

class IncapacidadesController extends Controller
{

    public function all()
    {
        $incapacidad = Incapacidade::where('Estado_id', 1)->get();
        return response()->json($incapacidad, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Fechainicio'  => 'required',
            'Dias'         => 'required',
            'Contingencia' => 'required',
            'Descripcion'  => 'required',
        ]);
        if ($validator->fails()) {
            $errores = $validator->errors();
            return response()->json($errores, 422);
        }

        $incapacidad = $this->getRange($request);

        if (!empty($incapacidad)) {
            return response()->json([
                'message' =>
                'Ya existe una incapacidad activa con fecha de inicio ' . $incapacidad->Fechainicio . ' y fecha final ' . $incapacidad->Fechafinal,
            ], 404);
        }

        Incapacidade::create([
            'Orden_id'         => $request->Orden_id,
            'Fechainicio'      => $request->Fechainicio,
            'Dias'             => $request->Dias,
            'Fechafinal'       => $request->Fechafinal,
            'Prorroga'         => $request->Prorroga,
            'Cie10_id'         => $request->Cie10_id,
            'Contingencia'     => $request->Contingencia,
            'Descripcion'      => $request->Descripcion,
            'Usuarioordena_id' => auth()->user()->id,
            'Usuarioedit_id'   => auth()->user()->id,
            'Laboraen'         => $request->Laboraen,
        ]);
        return response()->json([
            'message' => 'Incapacidad generada con exito!',
        ], 201);
    }

    public function getIncapacidadByCedula(Request $request)
    {

        $incapacidad = DB::select("exec dbo.Incapacidadespacientes" . "'$request->cedula'");

        if (isset($incapacidad)) {
            return response()->json($incapacidad, 201);
        }

        return response()->json([
            'message' => 'Incapacidad no encontrada',
        ], 404);
    }

    public function changeIncapacidadToAnulado(Request $request)
    {

        $incapacidad = Incapacidade::where('id', $request->Id)->first();

        $incapacidad->update([
            'Estado_id' => 26,
        ]);

        $msg = 'Anuló la incapacidad de ' . $request->Nombre . ' con fecha de inicio ' . $request->Inicio . ' y fecha de fin ' . $request->Fin;

        Auditoria::create([
            'Descripcion'        => $msg,
            'Tabla'              => 'Incapacidades',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $request->Id,
            'Motivo'             => $request->Observacion,
        ]);

        return response()->json(['message' => 'Incapacidad anulada de manera exitosa'], 200);
    }

    public function getIncapacidadesByActive()
    {
        $incapacidades = Incapacidade::select([
            'incapacidades.id as Consecutivo',
            'incapacidades.Fechainicio',
            'incapacidades.Dias',
            'incapacidades.Fechafinal',
            'p.Laboraen',
            'incapacidades.Prorroga',
            'incapacidades.Contingencia',
            'incapacidades.created_at as Fecha_Legalizacion',
            'c.Codigo_CIE10',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'p.Tipo_Doc',
            'p.Num_Doc',])
            ->leftjoin('cie10s as c', 'incapacidades.Cie10_id', 'c.id')
            ->join('ordens as o', 'incapacidades.Orden_id', 'o.id')
            ->join('cita_paciente as cp', 'o.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->get();
            
            return (new FastExcel($incapacidades))->download('file.xls');

        
    }

    private function getRange($request)
    {
        $incapacidades = Incapacidade::select(['incapacidades.*'])
            ->join('ordens as o', 'incapacidades.Orden_id', 'o.id')
            ->join('cita_paciente as c', 'o.Cita_id', 'c.id')
            ->join('pacientes as p', 'c.Paciente_id', 'p.id')
            ->where('p.Num_Doc', $request->Cedula)
            ->where('incapacidades.Estado_id', 1)
            ->whereBetween('incapacidades.Fechainicio', [$request->Fechainicio, $request->Fechafinal])
            ->whereBetween('incapacidades.Fechafinal', [$request->Fechainicio, $request->Fechafinal])
            ->first();

        return $incapacidades;
    }
    public function reporteIncapacidad(Request $request)
    {
        $request->tipo_informe = ($request->tipo_informe == 'Informe por fecha creación') ? 1 : 2;
        $incapacidad = Collect(DB::select("exec dbo.sp_incapacidades ?,?,?", [$request->f_inicial,$request->f_final,$request->tipo_informe]));
         $array = json_decode($incapacidad, true);
        return (new FastExcel($array))->download('file.xls');
    }
}