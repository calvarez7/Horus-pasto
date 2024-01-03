<?php

namespace App\Http\Controllers\Esquema;

use App\Modelos\notaenfermeria;
use App\Modelos\Orden;
use App\Modelos\Esquema;
use Illuminate\Http\Request;
use App\Modelos\Detallesquema;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EsquemaController extends Controller
{
    public function enableEsquemas()
    {
        $esquemas = Esquema::getRepository()->enableEsquemas()->get();
        return response()->json($esquemas, 200);
    }

    public function getDetalleEsquema()
    {
        $detalleEsquema = Detallesquema::getRepository()->getDetalleEsquema()->get();
        return response()->json($detalleEsquema, 200);
    }

    public function storeDetail(Request $request){
        Detallesquema::getRepository()->save($request);
        return response()->json("Registro exitoso", 200);
    }

    public function updateDetails(Request $request,Detallesquema $detallesquema){

        $detallesquema->update([
            'unidadMedida' => $request->unidadMedida,
            'indiceDosis' => $request->indiceDosis,
            'via' => $request->via,
            'frecuencia' => $request->frecuencia,
            'cantidadAplicaciones' => $request->cantidadAplicaciones,
            'diasAplicacion' => $request->diasAplicaciones,
            'descripcionDosis' => $request->descripcionDosis,
            'observaciones' => $request->observaciones,
            'dosis' => $request->dosis,
        ]);
        return response()->json([
            'message' => 'esquema Actualizado con Exito!',
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ciclos'              => 'required',
            'frecuenciaRepeat'    => 'required',
            'nombreEsquema'       => 'required|unique:esquemas',
            'abrevEsquema'        => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input = $request->all();
        $esquema = Esquema::create($input);
        return response()->json([
            'message' => 'Esquema creado con Exito!',
        ], 200);
    }

    public function show(Esquema $esquema)
    {
        $esquema = Esquema::find($esquema);
        if (!isset($esquema)) {
            return response()->json([
                'message' => 'El Esquema buscado no Existe!'
            ], 404);
        }
        return response()->json($esquema, 200);
    }

    public function update(Request $request, Esquema $esquema)
    {
        $esquema->update($request->all());
        return response()->json([
            'message' => 'esquema Actualizado con Exito!',
        ], 200);
    }

    public function available(Request $request, Esquema $esquema)
    {
        $esquema->update($request->all());
        return response()->json([
            'message' => 'esquema Actualizado con Exito!',
        ], 200);
    }

    public function programacionCitaAplicacion()
    {

    }

    public function saveNotaEnfermeria(Request $request){
        $notaEnfermeria = notaenfermeria::create($request->all());
        return response()->json([
            'message' => 'Nota creada con Exito!',
        ], 200);
    }

    public function getNotas($orden){
        $notas = notaenfermeria::whereIn("cita_paciente_id", function ($query) use ($orden) {
            $query->select('cita_paciente_relacion_id')
                ->from('citas_compuestas')
                ->where('cita_paciente_id', $orden);
        })->get();
        return response()->json($notas, 200);

    }

    public function editNotaEnfermeria(Request $request,$citaPaciente ){
        notaenfermeria::where('cita_paciente_id',$citaPaciente)->update($request->all());
        return response()->json([
            'message' => 'Nota Actualizado con Exito!',
        ], 200);
    }

    public function notas(Request $request)
    {
        $nota = notaenfermeria::select(['notaenfermerias.*', 'p.Num_Doc', 'p.Primer_Nom', 'p.SegundoNom', 'p.Primer_Ape', 'p.Segundo_Ape'])
                ->join('cita_paciente as cp', 'notaenfermerias.cita_paciente_id', 'cp.id')
                ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                ->where('p.Num_Doc', $request->cc)
                ->where('p.Estado_Afiliado', 1)
                ->get();
        return response()->json($nota, 200);
    }
    public function miNota($id)
    {
        $miNota = notaenfermeria::where('notaenfermerias.id', $id)->first();
        return response()->json($miNota, 200);
    }
}
