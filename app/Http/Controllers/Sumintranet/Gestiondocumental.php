<?php

namespace App\Http\Controllers\Sumintranet;

use App\Http\Controllers\Controller;
use App\Modelos\archivos_documentale;
use App\Modelos\documentale;
use App\Modelos\documentales_subcarpeta_gestione;
use App\Modelos\subcarpeta_gestione;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class Gestiondocumental extends Controller
{

    public function index(Request $request)
    {
        $ver = documentale::select('*')
            ->with(['verRelaciones' => function ($query) {
                $query->select('*')->with('relacion')->get();
            }])
            ->get();
        return response()->json($ver, 200);
    }

    public function ver()
    {
        return response()->json(documentale::all());
    }

    public function crearcarpeta(Request $request)
    {
        $filenametostore = 'adjuntos_intranet/' . $request->nombre;
        Storage::disk(config('filesystems.disksUse'))->makeDirectory($filenametostore);
        documentale::create([
            'nombre' => $request->nombre,
            'ruta' => $filenametostore,
            'usuario_id' => auth()->user()->id
        ]);
        return response()->json(['message' => 'creado con exito'], 200);
    }

    public function crearsubcarpeta(Request $request)
    {
        $d = documentale::select('nombre')->where('id', $request->carpeta_id)->first();
        $filenametostore = 'adjuntos_intranet/' . $d->nombre . '/' . $request->nombre;
        Storage::disk(config('filesystems.disksUse'))->makeDirectory($filenametostore);
        subcarpeta_gestione::create([
            'nombre' => $request->nombre,
            'ruta' =>  $filenametostore,
            'user_id' => auth()->user()->id,
            'carpeta_id' => $request->carpeta_id
        ]);
        return response()->json([
            'message' => 'crado con exito',
        ], 201);
    }

    public function subcarpetas($id)
    {
        $data = [
          'carpetas' => subcarpeta_gestione::where('carpeta_id',$id)->get(),
          'archivos' => archivos_documentale::where('carpeta_id',$id)->get()
        ];
        return response()->json($data);
    }

    public function vercarpetas()
    {
        $subcarpeta =  subcarpeta_gestione::select('id', 'nombre', 'ruta')->where('subcarpeta_id', '!=', null)->get();
        return response()->json($subcarpeta, 200);
    }

    public function crearcarpetasubcarpeta(Request $request)
    {
        $subcarpeta = subcarpeta_gestione::select('nombre', 'ruta', 'carpeta_id')->where('id', $request->subcarpeta_id)->first();
        $carpeta = documentale::select('nombre')->where('id', $subcarpeta->carpeta_id)->first();
        $filenametostore = 'adjuntos_intranet/' . $carpeta->nombre . '/' . $subcarpeta->nombre . '/' . $request->nombre;
        Storage::disk(config('filesystems.disksUse'))->makeDirectory($filenametostore);
        subcarpeta_gestione::create([
            'nombre' => $request->nombre,
            'ruta' =>  $filenametostore,
            'user_id' => auth()->user()->id,
            'subcarpeta_id' => $request->subcarpeta_id
        ]);
        return response()->json([
            'message' => 'crado con exito',
        ], 201);
    }

    public function show()
    {
        $carpeta = archivos_documentale::select(
            'archivos_documentales.id',
            'archivos_documentales.ruta',
            'archivos_documentales.nombre',
            'archivos_documentales.estado',
            'users.name as nombreusuario'
        )->leftjoin('users', 'users.id', 'archivos_documentales.user_id')->get();
        return response()->json($carpeta, 200);
    }

    public function buscarcarpeta($id)
    {
        $carpeta = subcarpeta_gestione::select('subcarpetas.id as subcarpeta_id', 'subcarpetas.nombre', 'subcarpetas.ruta', 'documentales.id')
            ->join('documentales', 'subcarpetas.carpeta_id', 'documentales.id')->where('documentales.id', $id)
            ->get();
        return response()->json($carpeta, 200);
    }

    public function shows($id)
    {
        $carpeta = subcarpeta_gestione::select('id', 'nombre', 'ruta')->where('subcarpeta_id', $id)->get();
        $arvhivo = archivos_documentale::select('id', 'nombre', 'ruta')->where('subcarpeta_id', $id)->get();
        return response()->json(['archivos' => $arvhivo, 'cartepas' => $carpeta], 200);
    }

    public function archivos(Request $request,$id,$folder)
    {
        $carpeta = (intval($folder) === 2?documentale::find($id):subcarpeta_gestione::find($id));

        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');
            $i = 0;
            foreach ($files as $file) {
                $fileName[$i] = $file->getClientOriginalName();
                $filenametostore[$i] = $carpeta->ruta. '/' . $fileName[$i];
                Storage::disk(config('filesystems.disksUse'))->put($filenametostore[$i], fopen($file, 'r+'));

                $archivos = new archivos_documentale();
                $archivos->nombre = $fileName[$i];
                $archivos->ruta = $filenametostore[$i];
                $archivos->user_id = auth()->user()->id;
                $archivos->estado = 1;
                if(intval($folder) === 2){
                    $archivos->carpeta_id = $id;
                }else{
                    $archivos->subcarpeta_id = $id;
                }
                $archivos->save();
            }
        }

        return response()->json(['message' => 'creado con exito'], 200);
    }

    public function descargar($id, Request $request)
    {
        $descarga = Storage::disk(config('filesystems.disksUse'))->download($request->ruta);
        return response()->json($descarga);
    }

    public function AgregarCarpeta(Request $request)
    {
        $rutas = subcarpeta_gestione::select(
            'nombre',
            'ruta'
        )->where('id', $request->subcarpeta_id)->first();
        $filenametostore =  $rutas->ruta . '/' . $request->nombre;
        Storage::disk('ftp')->makeDirectory($filenametostore);
        documentales_subcarpeta_gestione::create([
            'nombre' => $request->nombre,
            'ruta' => $filenametostore,
            'user_id' => auth()->user()->id,
            'subcarpeta_id' => $request->subcarpeta_id,
            'subcarpet_id' => $request->subcarpeta_id

        ]);
        return response()->json(['message' => 'creado con exito'], 200);
    }
    public function subcapetaAgregarCarpeta(Request $request)
    {
        $ruta = subcarpeta_gestione::select(
            'nombre',
            'ruta'
        )->where('id', $request->subcarpeta_id)->first();
        $filenametostore =  $ruta->ruta . '/' . $request->nombre;
        Storage::disk('ftp')->makeDirectory($filenametostore);
        subcarpeta_gestione::create([
            'nombre' => $request->nombre,
            'ruta' => $filenametostore,
            'user_id' => auth()->user()->id,
            'subcarpeta_id' => $request->subcarpeta_id
        ]);
        return response()->json(['message' => 'creado con exito'], 200);
    }

    public function showsubcarpetas($id)
    {
        $carpeta = subcarpeta_gestione::select('id', 'nombre', 'ruta')->where('subcarpeta_id', $id)->get();
        return response()->json($carpeta, 200);
    }

    public function subCarpetaEnCarpeta(Request $request){
        $filename = $request->rutaAnterior."/".$request->nombre;
        Storage::disk(config('filesystems.disksUse'))->makeDirectory($filename);
        subcarpeta_gestione::create([
            'nombre' => $request->nombre,
            'ruta' => $filename,
            'user_id' => auth()->user()->id,
            'carpeta_id' => $request->id
        ]);
        return response()->json(['message' => 'creado con exito']);
    }

    public function mostarSubcarpetaEnSubcarpeta($id){
        $data = [
            'carpetas' => subcarpeta_gestione::where('subcarpeta_id',$id)->get(),
            'archivos' => archivos_documentale::where('subcarpeta_id',$id)->get()
        ];
        return response()->json($data);
    }

    public function subCarpetaEnSubCarpeta(Request $request){
        $filename = $request->rutaAnterior."/".$request->nombre;
        Storage::disk(config('filesystems.disksUse'))->makeDirectory($filename);
        subcarpeta_gestione::create([
            'nombre' => $request->nombre,
            'ruta' => $filename,
            'user_id' => auth()->user()->id,
            'subcarpeta_id' => $request->id
        ]);
        return response()->json(['message' => 'creado con exito']);
    }

    public function migas(Request $request){
        $arrMiga = [];
        if(intval($request->tabla) === 1){
            $arrMiga[] = documentale::find($request->id);
        }else{
            $subCarpeta = subcarpeta_gestione::find($request->id);
            $arrMiga[] = $subCarpeta;
            if($subCarpeta->subcarpeta_id){
                $id = $subCarpeta->subcarpeta_id;
                $flag = 1;
                while ($flag == 1){
                    $sub = subcarpeta_gestione::find($id);
                    $arrMiga[] = $sub;
                    if($sub->carpeta_id){
                        $arrMiga[] = documentale::find($sub->carpeta_id);
                        $flag = 2;
                    }else{
                        $id = $sub->subcarpeta_id;
                    }
                }
            }else{
                $arrMiga[] = documentale::find($subCarpeta->carpeta_id);
            }
        }

//        dd($arrMiga);
        return response()->json($arrMiga);
    }

    public function getFiles($id,$carpeta){
        $data = [];
        if(intval($carpeta) === 2){
            $data = archivos_documentale::where('carpeta_id',$id)->get();
        }else{
            $data = archivos_documentale::where('subcarpeta_id',$id)->get();
        }
        return response()->json($data);
    }

    public function eliminarElemento(Request $request){
            archivos_documentale::destroy($request->id);
            Storage::disk(config('filesystems.disksUse'))->delete($request->ruta);
        return response()->json(['mensaje' => "archivo eliminado"]);
    }

    public function getFilesFilters($filtro){
        $data = [
            'archivos' => archivos_documentale::select([
                'archivos_documentales.*',
                'd.nombre as nombrePrimaria',
                'd.ruta as rutaPrimaria',
                'd.id as idPrimaria',
                's.nombre as nombreSecundaria',
                's.ruta as rutaSecundaria',
                's.id as idSecundaria',
                's.carpeta_id as vienePrimaria',
                's.subcarpeta_id as vieneSecundaria'
            ])->where('archivos_documentales.nombre','LIKE','%'.$filtro.'%')
                ->leftjoin('documentales as d','d.id','archivos_documentales.carpeta_id')
                ->leftjoin('subcarpetas as s','s.id','archivos_documentales.subcarpeta_id')
                ->get(),
            'carpetasPrincipales' => documentale::where('nombre','LIKE','%'.$filtro.'%')->get(),
            'carpetaSecundarias' => subcarpeta_gestione::
                select([
                    'subcarpetas.*',
                'd.nombre as nombrePrimaria',
                'd.ruta as rutaPrimaria',
                'd.id as idPrimaria',
                's.nombre as nombreSecundaria',
                's.ruta as rutaSecundaria',
                's.id as idSecundaria',
                ])
                ->where('subcarpetas.nombre','LIKE','%'.$filtro.'%')
                ->leftjoin('documentales as d','d.id','subcarpetas.carpeta_id')
                ->leftjoin('subcarpetas as s','s.id','subcarpetas.subcarpeta_id')
                ->get()
        ];
        return response()->json($data);

    }

    public function migasAutogeneradas(Request $request){
        $info = [];
        $carpetas = explode('/',$request->rutas);
        foreach ($carpetas as $key =>  $carpeta){
        if($key === 1){
            $data = documentale::where('nombre',$carpeta)->first();
            $info[] = [
                'id' => $data->id,
                'nombre' => $data->nombre,
                'ruta' => $data->ruta,
                'tipo' => 'carpeta'
            ];
        }elseif ($key >= 2){
            $data = subcarpeta_gestione::where('nombre',$carpeta)->first();
            $info[] = [
                'id' => $data->id,
                'nombre' => $data->nombre,
                'ruta' => $data->ruta,
                'tipo' => 'subCarpeta'
            ];
        }
        }
        return response()->json($info);
    }
}
