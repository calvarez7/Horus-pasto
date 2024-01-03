<?php

namespace App\Http\Controllers\Conteo;

use App\Http\Controllers\Controller;
use App\Modelos\bodegarticulo;
use App\Modelos\Conteoinventario;
use App\Modelos\InventarioBodega;
use App\Modelos\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class ConteoinvController extends Controller
{
    public function fetchConteoInventario()
    {
        $conteo = Conteoinventario::all();
        return response()->json($conteo, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'conteo1' => 'integer',
            'conteo2' => 'integer',
            'conteo3' => 'integer',
            'conteo4' => 'integer',
        ]);
        if ($validate->fails()) {
            return response()->json(['errores' => $validate->errors()], 422);
        }
        $conteo = Conteoinventario::where('Lote_id', $request->Lote_id)->first();

        if (isset($request->Conteo1) || isset($request->Conteo2)) {
            if (!isset($conteo)) {
                $conteo = Conteoinventario::create([
                    'Lote_id'     => $request->Lote_id,
                    'Conteo1'     => $request->Conteo1,
                    'Conteo2'     => $request->Conteo2,
                    'UserCrea_id' => auth()->user()->id,
                ]);
                return response()->json([
                    'message' => 'Conteo almacenado con exito',
                ]);
            }
            $conteo->update($request->all());
        }

        if (isset($conteo->Conteo1) && isset($conteo->Conteo2)) {
            if ($conteo->Conteo1 == $conteo->Conteo2) {
                $conteo->Value1 = $conteo->Conteo1;
                $conteo->save();

            } elseif ($conteo->Conteo1 != $conteo->Conteo2) {
                $conteo->Value1    = 0;
                $conteo->Estado_id = 4;
                $conteo->save();
            }
        } elseif (isset($conteo->Conteo1)) {
            $conteo->Conteo2 = $request->conteo2;
            $conteo->save();
        } elseif (isset($conteo->Conteo2)) {
            $conteo->Conteo1 = $request->conteo1;
            $conteo->save();
        }

        if (isset($conteo->Conteo1) && isset($conteo->Conteo2)) {
            if (isset($request->Conteo3)) {
                if ($conteo->Value1 == 0) {
                    $conteo->Conteo3 = $request->Conteo3;
                    $conteo->save();
                    if ($conteo->Conteo3 == $conteo->Conteo1) {
                        $conteo->Value1    = $conteo->Conteo1;
                        $conteo->Estado_id = 34;
                        $conteo->save();
                    } elseif ($conteo->Conteo3 == $conteo->Conteo2) {
                        $conteo->Value1    = $conteo->Conteo2;
                        $conteo->Estado_id = 34;
                        $conteo->save();
                    } else {
                        $conteo->Value1 = 0;
                        $conteo->save();
                    }
                }
            }
        }

        if (isset($conteo->Conteo3)) {
            if (isset($request->Conteo4)) {
                if ($conteo->Value1 == 0) {
                    $conteo->Conteo4 = $request->Conteo4;
                    $conteo->save();
                    if ($conteo->Conteo4 == $conteo->Conteo1) {
                        $conteo->Value1    = $conteo->Conteo1;
                        $conteo->Estado_id = 34;
                        $conteo->save();
                    } elseif ($conteo->Conteo4 == $conteo->Conteo2) {
                        $conteo->Value1    = $conteo->Conteo2;
                        $conteo->Estado_id = 34;
                        $conteo->save();
                    } elseif ($conteo->Conteo4 == $conteo->Conteo3) {
                        $conteo->Value1    = $conteo->Conteo4;
                        $conteo->Estado_id = 34;
                        $conteo->save();
                    } else {
                        $conteo->Value1 = 0;
                        $conteo->save();
                    }
                }
            }
        }
    }

    public function cerrarConteo($codigoInventario,Request $request)
    {
        $datos = Conteoinventario::where('inventario_bodega_id',$codigoInventario)->get();
        foreach ($datos as $conteo) {
            if (is_numeric($conteo->Value1)) {
                $lotes                    = Lote::where('id', $conteo->Lote_id)->first();
                $lotes->Cantidadisponible = $conteo->Value1;
                $lotes->save();

                $bodegarticulos = bodegarticulo::where('id', $lotes->Bodegarticulo_id)->first();
                $sumalotes      = Lote::selectRaw('SUM(Cantidadisponible) as sum')
                    ->where('Bodegarticulo_id', $lotes->Bodegarticulo_id)->first();
                $bodegarticulos->Stock = $sumalotes->sum;
                $bodegarticulos->save();

                $conteo->update(['Estado_id' => 34]);
            }
        }
        InventarioBodega::find($codigoInventario)->update(['estado_id' => 34]);

        return response()->json([
            'message' => 'Los conteos iguales se ajustaron con Exito!',
        ], 200);

    }

    public function cierreConteo1($codigoInventario,Request $request)
    {
        $datos = $request->all();
            Conteoinventario::where('inventario_bodega_id',$codigoInventario)->where('inventario_bodega_id',$codigoInventario)->update(['Estado_id' => 30]);
        InventarioBodega::find($codigoInventario)->update(['estado_id' => 30]);
        return response()->json([
            'message' => 'Conteo 1 cerrado con Exito!',
        ], 200);
    }

    public function cierreConteo2($codigoInventario,Request $request)
    {
        $datos = $request->all();
        Conteoinventario::where('inventario_bodega_id',$codigoInventario)->update(['Estado_id' => 31]);
        InventarioBodega::find($codigoInventario)->update(['estado_id' => 31]);
        return response()->json([
            'message' => 'Conteo 3 cerrado con Exito!',
        ], 200);
    }

    public function cierreConteo3($codigoInventario,Request $request)
    {
        $datos = $request->all();
        Conteoinventario::where('inventario_bodega_id',$codigoInventario)->update(['Estado_id' => 32]);
        InventarioBodega::find($codigoInventario)->update(['estado_id' => 32]);
        return response()->json([
            'message' => 'Conteo 3 cerrado con Exito!',
        ], 200);
    }
    public function exportSaldos(Request $request)
    {
        $exportarconteos = DB::select("exec dbo.exportarConteo" . " '$request->fechaInventario'");
        return (new FastExcel($exportarconteos))->download('file.xls');
    }

    public function guardarProgreso(Request $request){
        foreach ($request->all() as $conteo){
         $loteConteo = Conteoinventario::find($conteo["id"]);
         switch ($loteConteo->Estado_id){
             case 1:
                 $loteConteo->Conteo1 = $conteo["cantidad"];
                 if($loteConteo->saldo_inicial == $conteo["cantidad"]){
                     $loteConteo->Value1 = $conteo["cantidad"];
                 }
                 $loteConteo->save();
                 break;
             case 30:
                 $loteConteo->Conteo2 = $conteo["cantidad"];
                 if($loteConteo->Conteo1 == $conteo["cantidad"] || $loteConteo->saldo_inicial == $conteo["cantidad"]){
                     $loteConteo->Value1 = $conteo["cantidad"];
                 }
                 $loteConteo->save();
                 break;
             case 31:
                 $loteConteo->Conteo3 = $conteo["cantidad"];
                 $loteConteo->Value1 = $conteo["cantidad"];
                 $loteConteo->save();
                 break;
         }

        }
    }

    public function proximosConteos($codigoInventario){
        $inventario = InventarioBodega::find($codigoInventario);
        $query = bodegarticulo::select([
            "d.id as idLote",
            "c.Codigo",
            "b.Producto",
            "b.Titular",
            "d.Numlote",
            "bd.Nombre as Bodega",
            "d.Fvence",
            "e.saldo_inicial",
            "e.Conteo1 as Conteo_1",
            DB::raw("(CASE WHEN e.Conteo2 is null THEN '0' ELSE e.Conteo2 END) as Conteo_2"),
            DB::raw("(CASE WHEN e.conteo3 is null THEN '0' ELSE e.conteo3 END) as Conteo_3"),
            "e.Value1 as saldo_final",
            "es.Nombre",
            "bodegarticulos.id as Bodegaarticulo_id"
        ])->join('detallearticulos as b','bodegarticulos.Detallearticulo_id',"b.id")
            ->join('bodegas as bd','bodegarticulos.Bodega_id','bd.id')
            ->join('codesumis as c','b.Codesumi_id','c.id')
            ->join('lotes as d','bodegarticulos.id','d.Bodegarticulo_id')
            ->leftjoin('conteoinventarios as e','d.id','e.Lote_id')
            ->leftjoin('estados as es','e.estado_id','es.id')
            ->leftjoin('inventario_bodegas as ib','ib.id','e.inventario_bodega_id')
            ->where('e.inventario_bodega_id',$codigoInventario);
        switch (intval($inventario->estado_id)){
            case 1:
                $query->whereNull('e.Value1')->whereNotNull('e.Conteo1');
                break;
            case 30:
                $query->whereNull('e.Value1')->whereNotNull('e.Conteo2');
                break;
            case 31:
                $query->whereNull('e.Value1')->whereNull('e.Conteo3');
                break;
        }
        return (new FastExcel($query->get()))->download('file.xls');
    }
}
