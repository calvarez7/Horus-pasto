<?php

namespace App\Http\Controllers\Eventos;

use App\Modelos\Evento;
use App\Modelos\Tipo_evento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modelos\Clasificacion_evento;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function all()
    {
        $evento = Evento::select('nombre', 'id')->where('estado_id', 1)->get();
        return response()->json($evento, 200);
    }

    public function allsumimedical()
    {
        $evento = Evento::select('nombre', 'id')
        ->where('estado_id', 1)
        ->where('tipo_id', 12)
        ->get();
        return response()->json($evento, 200);
    }

    public function getclasificacion(Evento $evento)
    {
        $clasificacion = Clasificacion_evento::select('nombre', 'id')->where('evento_id', $evento->id)->get();
        return response()->json($clasificacion, 200);
    }

    public function getTipoevento(Clasificacion_evento $clasificacion)
    {
        $tipoevento = Tipo_evento::select('nombre', 'id')
        ->where('clasificacionevento_id', $clasificacion->id)->get();
        return response()->json($tipoevento, 200);
    }

    public function allsuceso(){

        $evento = Evento::select('eventos.nombre', 'eventos.id', 'estados.nombre as estado', 'estados.id as estadoId')
        ->join('estados', 'eventos.estado_id', 'estados.id')
        ->where('eventos.tipo_id', 12)
        ->get();

        return response()->json($evento, 200);
    }

    public function allclasificaciones(){

        $clasificacion = Clasificacion_evento::select('clasificacion_eventos.nombre', 'clasificacion_eventos.id',
        'eventos.nombre as suceso')
        ->join('eventos', 'clasificacion_eventos.evento_id', 'eventos.id')
        ->get();

        return response()->json($clasificacion, 200);

    }

    public function alltipoactividades(){

        $tipoevento = Tipo_evento::select('tipo_eventos.nombre', 'tipo_eventos.id', 'clasificacion_eventos.nombre as clasificacion')
        ->join('clasificacion_eventos', 'tipo_eventos.clasificacionevento_id', 'clasificacion_eventos.id')
        ->get();

        return response()->json($tipoevento, 200);

    }

    public function saveSuceso(Request $request)
    {
        Evento::create([
            'nombre' => $request->suceso,
            'estado_id' => 1,
            'tipo_id' => 12
        ]);

        return response()->json([
            'message' => 'Suceso creado con exito!',
        ], 200);
    }

    public function saveClasificacion(Request $request)
    {
        Clasificacion_evento::create($request->all());

        return response()->json([
            'message' => 'Clasificación creada con exito!',
        ], 200);
    }

    public function saveTipoactividad(Request $request)
    {
        Tipo_evento::create($request->all());

        return response()->json([
            'message' => 'Tipo de actividad creada con exito!',
        ], 200);
    }

    public function updateEstadoSuceso($eventoId, Request $request)
    {
        Evento::where('id', $eventoId)->update([
            'estado_id' => $request->estado_id
        ]);

        return response()->json([
            'message' => 'Suceso actualizado con exito!',
        ], 200);
    }
}