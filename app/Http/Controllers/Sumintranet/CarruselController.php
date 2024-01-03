<?php

namespace App\Http\Controllers\Sumintranet;

use App\Http\Controllers\Controller;
use App\Modelos\blog;
use App\Modelos\carrusel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CarruselController extends Controller
{
    public function carrusel()
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');
        $carusel = carrusel::select(DB::raw('*'))->where('estado_id', 1)->get();
        return response()->json($carusel, 200);
    }

    public function index()
    {
        $carusel = carrusel::select(
            'carousels.id',
            'carousels.enlace',
            'carousels.nombre',
            'estados.Nombre as Nombre_estado',
            'carousels.fecha_inicio',
            'carousels.fecha_final',
            'estados.id as idestado',
            'carousels.created_at',
            'carousels.imagen'
        )
            ->join('estados', 'carousels.estado_id', 'estados.id')->orderBy('carousels.estado_id', 'asc')->get();
        return response()->json($carusel, 200);
    }

    public function store(Request $request)
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');

        $validar = Validator::make($request->all(), [
            'imagen' => 'required',
            'nombre' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required',
        ]);
        if ($validar->fails()) {
            return response()->json(["errors" => $validar->getMessageBag()], 422);
        }
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/storage/imagenesintranet', $name);

            if ($request->fecha_inicio > $fecha) {
                $estado = 2;
            }
            if ($request->fecha_inicio == $fecha) {
                $estado = 1;
            }
            if($request->enlace == null){
                $dato = '';
            }else{
                $dato = $request->enlace;
            }
            carrusel::create([
                'imagen' => $name,
                'estado_id' => $estado,
                'nombre' => $request->nombre,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_final' => $request->fecha_final,
                'enlace' => $dato,
                'created_at' => Carbon::now()
            ]);
        }
        return response()->json(['message' => 'Creado con exito']);
    }

    public function estado(Request $request, $id)
    {
        if ($request->Nombre_estado == '1') {
            carrusel::where('id', $id)->update([
                'estado_id' => 2,
                'updated_at' => Carbon::now()
            ]);
        } else if ($request->Nombre_estado == '2') {
            carrusel::where('id', $id)->update([
                'estado_id' => 1,
                'updated_at' => Carbon::now()
            ]);
        }
        return response()->json(['message' => 'El estado fue modificado con exito']);
    }

    public function updatecarrusel(Request $request)
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $Nombre_Imagen = time() . $archivo->getClientOriginalName();
            $archivo->move(public_path() . '/storage/imagenesintranet', $Nombre_Imagen);

        }
        else{
            $Nombre_Imagen = $request->imagen;
        }
        if ($request->fecha_inicio > $fecha) {
            carrusel::where('id', $request->id)->update([
                'estado_id' => 2,
            ]);
        }
        if ($request->fecha_inicio == $fecha) {
            carrusel::where('id', $request->id)->update([
                'estado_id' => 1,
            ]);
        }if($request->enlace == null){
            $dato = '';
        }else{
            $dato = $request->enlace;
        }
        carrusel::where('id', $request->id)->update([
            'fecha_inicio' => $request->fecha_inicio,
            'nombre' => $request->nombre,
            'imagen' => $Nombre_Imagen,
            'enlace' => $dato,
            'updated_at' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Usuario actualizado con exito !'
        ], 200);
    }
}
