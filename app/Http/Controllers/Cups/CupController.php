<?php

namespace App\Http\Controllers\Cups;

use App\Modelos\Cup;
use App\Modelos\Cuporden;
use App\Modelos\DetallePaqueteServicio;
use App\Modelos\PaqueteServicio;
use App\Modelos\Pqrsf;
use App\Modelos\Familia;
use App\Modelos\Auditoria;
use App\Modelos\Cupspqrsf;
use App\Modelos\Cupfamilia;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class CupController extends Controller
{
    public function all()
    {
        $cups = Cup::all();
        return response()->json($cups, 200);
    }

    public function update(Cup $cup, Request $request)
    {
        $nombre = 'undefined';
        $msg = 'Actualizó';
        $cambio = false;
        if ($request->Requiere_auditoria != $cup->Requiere_auditoria) {
            $msg = $msg . ' requiere auditoria de "' . $cup->Requiere_auditoria . '" a "' . $request->Requiere_auditoria . '"';
            $cambio = true;
        }
        if ($request->Nivelordenamiento != $cup->Nivelordenamiento) {
            $msg = $msg . ' nivel de ordenamiento de "' . $cup->Nivelordenamiento . '" a "' . $request->Nivelordenamiento . '"';
            $cambio = true;
        }
        if ($request->Peridiocidad != $cup->Peridiocidad) {
            $msg = $msg . ' peridiocidad de "' . $cup->Peridiocidad . '" a "' . $request->Peridiocidad . '"';
            $cambio = true;
        }
        if ($request->Cantidadmaxordenar != $cup->Cantidadmaxordenar) {
            $msg = $msg . ' cantidad máxima de "' . $cup->Cantidadmaxordenar . '" a "' . $request->Cantidadmaxordenar . '"';
            $cambio = true;
        }
        if ($request->Nombre != $cup->Nombre) {
            $msg = $msg . ' nombre de "' . $cup->Nombre . '" a "' . $request->Nombre . '"';
            $cambio = true;
            $nombre = strtoupper($request->Nombre);
        }

        if (!$cambio) {
            return;
        }

        $cup->update([
            'Requiere_auditoria' => $request->Requiere_auditoria,
            'Nivelordenamiento' => $request->Nivelordenamiento,
            'Peridiocidad' => $request->Peridiocidad,
            'Cantidadmaxordenar' => $request->Cantidadmaxordenar,
            'Nombre' => ($nombre == 'undefined' ? $request->Nombre : $nombre)
        ]);

        Auditoria::create([
            'Descripcion' => $msg,
            'Tabla' => 'Cups',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id' => $cup->id,
            'Motivo' => '',
        ]);

        return response()->json([], 200);
    }

    public function cupsOrden()
    {
        $niveles = auth()->user()->getRepository()->getQueryLevelItemsOrder();

        $cups = Cup::select('cups.*')
            ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
            ->join('familias as f', 'cf.Familia_id', 'f.id')
            ->where('f.id', '<>', 115)
            ->where('f.Tipofamilia_id', 3)
            ->whereIn('cups.Nivelordenamiento', $niveles)
            ->get();

        return response()->json($cups, 200);
    }

    public function cupsOrdenInterconsulta(citapaciente $citapaciente)
    {
        $niveles = auth()->user()->getRepository()->getQueryLevelItemsOrder();

        $cups = Cup::select('cups.*')
            ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
            ->join('familias as f', 'cf.Familia_id', 'f.id')
            ->where('f.id', 115)
            ->where('f.Tipofamilia_id', 3)
            ->whereIn('cups.Nivelordenamiento', $niveles)
            ->get();

        return response()->json($cups, 200);
    }

    public function serviciosOrden()
    {
        $servicios = Familia::where('Tipofamilia_id', 3)->get();

        return response()->json($servicios, 200);
    }

    public function cupsCapitulo(Request $request)
    {
        $familias = [];
        foreach ($request->Familia_id as $familia) {
            array_push($familias, $familia['id']);
        }

        $cups = Cup::select(['cups.*', 'cf.Familia_id'])
            ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
            ->whereIn('cf.Familia_id', $familias)
            ->get();

        return response()->json($cups, 200);
    }

    public function searchCup(Request $request)
    {
        $cups = Cup::where('Codigo', 'like', '%' . $request->search . '%')
            ->orWhere('Nombre', 'like', '%' . $request->search . '%')
            ->get();

        return response()->json($cups, 200);
    }

    public function pqrsfCups(Pqrsf $pqrsf)
    {
        $pqrsfcups = Cupspqrsf::select(['Cups.Nombre', 'Cups.id'])
            ->join('Cups', 'Cupspqrsf.Cup_id', 'Cups.id')
            ->where('Pqrsf_id', $pqrsf->id)
            ->get();
        return response()->json($pqrsfcups, 201);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Codigo' => 'required|unique:Cups',
            'Nombre' => 'required',
            'Requiere_auditoria' => 'required',
            'Nivelordenamiento' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }


        $nombre = strtoupper($request->Nombre);
        $codigo = strtoupper($request->Codigo);
        $cup = Cup::create([
            'Codigo' => $codigo,
            'Nombre' => $nombre,
            'Requiere_auditoria' => $request->Requiere_auditoria,
            'Peridiocidad' => ($request->Peridiocidad == '' ? null : $request->Peridiocidad),
            'Nivelordenamiento' => $request->Nivelordenamiento,
            'Cantidadmaxordenar' => ($request->Cantidadmaxordenar == null ? 1 : $request->Cantidadmaxordenar)
        ]);

        foreach ($request->familia as $familia_id) {

            $existeFamilia = Cupfamilia::join('familias as f', 'cupfamilias.Familia_id', 'f.id')
                ->join('tipofamilias as tf', 'f.Tipofamilia_id', 'tf.id')
                ->where('tf.id', 4)
                ->where('cupfamilias.Cup_id', $cup->id)
                ->first();

            if (!$existeFamilia) {
                Cupfamilia::create([
                    'Cup_id' => $cup->id,
                    'Familia_id' => $familia_id
                ]);
            }

        }

        Auditoria::create([
            'Descripcion' => 'Creo cup',
            'Tabla' => 'cups',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id' => $cup->id
        ]);

        return response()->json([
            'message' => 'Cup creado con exito!'
        ], 201);
    }

    public function import(Request $request)
    {

        $noRegistra = [];
        $registra = [];
        $msg = '';

        (new FastExcel)->import($request->data, function ($line) use (&$noRegistra, &$registra, &$msg) {
            $codigo_string = (string)$line["Codigo"];
            $cup = Cup::where('Codigo', $codigo_string)->first();
            if ($cup) {
                $registra[] = $cup;
            } elseif (!$cup) {
                if ($line["Codigo"] == '' || $line["Nombre"] == '' || $line["Requiere autorizacion"] == '' || $line["Nivel ordenamiento"] == '') {
                    $msg = 'Hay campos obligatorios vacios, revisa: ( Codigo, Nombre, Requiere autorizacion, Nivel ordenamiento)';
                } else {
                    $noRegistra[] = $line;
                }
            }
        });

        if ($msg !== '') {
            return response()->json([
                'message' => $msg
            ], 400);
        } else if ($registra) {
            return response()->json([
                $registra
            ], 400);
        } else {
            foreach ($noRegistra as $key) {
                if ($key['Codigo'] !== '') {

                    $codigo = strtoupper($key['Codigo']);
                    $nombre = strtoupper($key['Nombre']);
                    $autorizacion = strtoupper($key['Requiere autorizacion']);

                    $cup_create = Cup::create([
                        'Codigo' => $codigo,
                        'Nombre' => $nombre,
                        'Requiere_auditoria' => $autorizacion,
                        'Peridiocidad' => ($key['Periocidad'] == '' ? null : $key['Periocidad']),
                        'Nivelordenamiento' => $key['Nivel ordenamiento'],
                        'Cantidadmaxordenar' => ($key['Cantidad maxima ordenar'] == '' ? 1 : $key['Cantidad maxima ordenar'])
                    ]);

                    foreach ($request->familia as $familia_id) {

                        $existeFamilia = Cupfamilia::join('familias as f', 'cupfamilias.Familia_id', 'f.id')
                            ->join('tipofamilias as tf', 'f.Tipofamilia_id', 'tf.id')
                            ->where('tf.id', 4)
                            ->where('cupfamilias.Cup_id', $cup_create->id)
                            ->first();

                        if (!$existeFamilia) {
                            Cupfamilia::create([
                                'Cup_id' => $cup_create->id,
                                'Familia_id' => $familia_id
                            ]);
                        }

                    }

                    Auditoria::create([
                        'Descripcion' => 'Creo cup',
                        'Tabla' => 'cups',
                        'Usuariomodifica_id' => auth()->user()->id,
                        'Model_id' => $cup_create->id
                    ]);
                }
            }

            return response()->json([
                'message' => 'Carga de cups con exito!'
            ], 200);

        }

    }

    public function cupfamilias(Request $request)
    {

        $cupfamilia = Cupfamilia::select('f.Nombre', 'f.id')
            ->where('cupfamilias.Cup_id', $request->cup_id)
            ->join('familias as f', 'cupfamilias.Familia_id', 'f.id')
            ->get();

        return response()->json($cupfamilia, 200);
    }

    public function addcupfamilias(Request $request)
    {

        $msg = '';

        foreach ($request->familia as $familia_id) {

            $existeFamilia = Cupfamilia::join('familias as f', 'cupfamilias.Familia_id', 'f.id')
                ->join('tipofamilias as tf', 'f.Tipofamilia_id', 'tf.id')
                ->where('tf.id', 4)
                ->where('cupfamilias.Cup_id', $request->cup_id)
                ->count();

            $tipoFamilia = Familia::where('id', $familia_id)
                ->where('Tipofamilia_id', 4)
                ->count();

            if ($existeFamilia == $tipoFamilia) {
                $msg = 'Este cup ya tiene una familia de capitulo!';
            } else {
                $familias[] = $familia_id;
            }

        }

        if ($msg !== '') {
            return response()->json([
                'message' => $msg
            ], 400);
        } else {
            foreach ($familias as $key) {
                Cupfamilia::create([
                    'Cup_id' => $request->cup_id,
                    'Familia_id' => $key
                ]);
            }

            return response()->json([
                'message' => 'Familia agreada al cup con exito!'
            ], 200);

        }

    }

    public function cupsEntidades($entidad)
    {
        $cups = Cup::select('cups.id', 'cups.Codigo', 'cups.Nombre')
            ->join('cupordens as c', 'cups.id', 'c.Cup_id')
            ->join('ordens as o', 'c.Orden_id', 'o.id')
            ->join('cita_paciente as  cp', 'o.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->where('p.entidad_id', $entidad)
            ->distinct()
            ->get();
        return response()->json($cups);
    }

    public function allEducacion()
    {
        $cups = Cup::select('cups.*')
            ->whereIn('cups.id', [2274, 2275, 2276, 2277, 2278, 2279, 2280, 2281, 2210, 2282, 2283, 2284, 2285, 13329])
            ->get();
        return response()->json($cups, 200);
    }

    public function paqueteServicios(){
        return response()->json(PaqueteServicio::all());
    }
    public function guardarPaquete(Request $request){
        $paqueteServicio = PaqueteServicio::find($request->cabezera["id"]);
        if(!$paqueteServicio){
            $paqueteServicio = PaqueteServicio::create([
                    'estado_id' => $request->cabezera["estado_id"],
                    'nombre' => $request->cabezera["nombre"]
                ]);
        }else{
            $paqueteServicio->estado_id = $request->cabezera["estado_id"];
            $paqueteServicio->nombre = $request->cabezera["nombre"];
            $paqueteServicio->save();
        }
        DetallePaqueteServicio::where('paquete_servicio_id', $paqueteServicio->id)->delete();
        foreach ($request->procedimientos as $procedimiento){
            DetallePaqueteServicio::create([
                "cantidad" => intval($procedimiento["cantidad"]),
                "cup_id" => intval($procedimiento["cup_id"]),
                "paquete_servicio_id" => $paqueteServicio->id
            ]);
        }
        return response()->json(["message" => "registro Exitoso"]);
    }
}
