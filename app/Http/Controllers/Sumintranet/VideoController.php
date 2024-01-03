<?php

namespace App\Http\Controllers\Sumintranet;

use App\Http\Controllers\Controller;
use App\Modelos\blog;
use App\Modelos\carrusel;
use App\Modelos\contadorvideo;
use App\Modelos\Empleado;
use App\Modelos\video;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        $datos = video::select(
            'videos.id',
            'videos.nombre',
            'videos.link',
            'videos.fecha_inicio',
            'videos.fecha_final',
            'estados.Nombre as Nombre_estado',
            'estados.id as idestado',
            'videos.created_at',
        )->withCount('relacion')->join('estados', 'videos.estado_id', 'estados.id')->orderBy('videos.estado_id', 'asc')->get();
        return response()->json($datos, 200);
    }

    public function multimedia()
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');
        $resultado = video::select(DB::raw('*'))->withCount('relacion')->where('estado_id', 1)->get();
        return response()->json($resultado, 200);
    }

    public function store(Request $request)
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');
        $validar = Validator::make($request->all(), [
            'link' => 'required',
            'nombre' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required',
        ]);

        if ($validar->fails()) {
            return response()->json(["errors" => $validar->getMessageBag()], 422);
        }

        if ($request->fecha_inicio > $fecha) {
            $estado = 2;
        }
        if ($request->fecha_inicio == $fecha) {
            $estado = 1;
        }
        video::create([
            'link' => $request->link,
            'estado_id' => $estado,
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_final' => $request->fecha_final,
            'created_at' => Carbon::now()
        ]);
        return response()->json(['message' => 'Creado con exito']);
    }

    public function estado(Request $request, $id)
    {
        if ($request->Nombre_estado == '1') {
            video::where('id', $id)->update([
                'estado_id' => 2,
                'updated_at' => Carbon::now()
            ]);
        } else if ($request->Nombre_estado == '2') {
            video::where('id', $id)->update([
                'estado_id' => 1,
                'updated_at' => Carbon::now()
            ]);
        }
        return response()->json(['message' => 'El estado fue modificado con exito']);
    }

    public function conteovideo($id)
    {
        $guardar =  contadorvideo::create([
            'visto' => 'true', 'user_id' => auth()->user()->id, 'video_id' => $id
        ]);
        return response()->json($guardar, 200);
    }

    public function verusuarios($id)
    {
        $ver = contadorvideo::select(
            DB::raw("(CASE WHEN contadorvideos.visto = '1' THEN 'visto' ELSE 'no visto' END) AS estado"),
            DB::raw("concat(users.name,' ',users.apellido) as nombre"),
            DB::raw("count(users.id) as usuario")
        )
            ->join('users', 'users.id', 'contadorvideos.user_id')
            ->where('contadorvideos.video_id', $id)->groupby(
                'contadorvideos.visto',
                'users.name',
                'users.apellido',
                'contadorvideos.video_id'
            )->get();
        return response()->json($ver, 200);
    }

    public function actualizarvideo(Request $request, $id)
    {

        if ($request->link == null) {
            $dato = '';
        } else {
            $dato = $request->link;
        }
        video::where('id', $id)->update([
            "fecha_final" => $request->fecha_final,
            "fecha_inicio" => $request->fecha_inicio,
            "link" => $dato,
            "nombre" => $request->nombre
        ]);
    }
}
