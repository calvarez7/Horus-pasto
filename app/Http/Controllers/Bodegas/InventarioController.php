<?php

namespace App\Http\Controllers\Bodegas;

use App\User;
use App\Modelos\Lote;
use App\Modelos\Orden;
use App\Modelos\Bodega;
use App\Modelos\Movimiento;
use Illuminate\Http\Request;
use App\Modelos\bodegarticulo;
use App\Modelos\Detallearticulo;
use App\Modelos\Bodegatransacion;
use App\Modelos\Conteoinventario;
use App\Modelos\InventarioBodega;
use App\Modelos\Solicitudbodegas;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\DetalleSolicitudBodega;
use Illuminate\Support\Facades\Validator;

class InventarioController extends Controller
{

    public function generar($codigoBodega){
     $inventario  = InventarioBodega::create([
         'bodega_id' => $codigoBodega,
         'estado_id' => 1
     ]);
     $this->generarConteos($inventario);

     $arrData = [
         "mensaje" => "Inventario Generado",
         "codigoInventario" => $inventario->id
     ];
        return response()->json($arrData,200);
    }

    private function generarConteos($inventario){
        $lotes = Lote::select([
            'lotes.*'
        ])->join('bodegarticulos as ba','lotes.Bodegarticulo_id','ba.id')
            ->join('bodegas as b','ba.Bodega_id','b.id')
            ->where('b.id',$inventario->bodega_id)
            ->where('Cantidadisponible','>',0)
            ->get();
        foreach ($lotes as $lote){
            Conteoinventario::create([
                'Lote_id' => $lote->id,
                'inventario_bodega_id' => $inventario->id,
                'Estado_id' => 1,
                'saldo_inicial' => $lote->Cantidadisponible,
                'UserCrea_id' => auth()->id()
            ]);
        }
    }

    public function conteosIncompletos($codigoInventario){
        $conteos = [];
        $conteos["faltantes1"] = Conteoinventario::where('inventario_bodega_id',$codigoInventario)->whereNull('Conteo1')->count();
        $conteos["faltantes2"] = Conteoinventario::where('inventario_bodega_id',$codigoInventario)->whereRaw('saldo_inicial != Conteo1')->whereNull('Conteo2')->count();
        $conteos["faltantes3"] = Conteoinventario::where('inventario_bodega_id',$codigoInventario)->whereRaw('saldo_inicial != Conteo2')->whereRaw('Conteo1 != Conteo2')->whereNull('Conteo3')->count();
        $conteos["estadoInventario"] = InventarioBodega::find($codigoInventario)->estado_id;
        $query = bodegarticulo::select([
            "d.id as idLote",
            "b.Producto",
            "d.Numlote",
            "bodegarticulos.Bodega_id",
            "d.Fvence",
            "d.Cantidadisponible",
            "e.Conteo1",
            "e.Conteo2",
            "e.Conteo3",
            "e.Conteo4",
            "e.Value1",
            "e.Estado_id",
            "e.saldo_inicial",
            "e.id as idConteo",
            "bodegarticulos.id as Bodegaarticulo_id",
            "b.codigo as Codigo",
            "t.nombre as Titular"
        ])->join('detallearticulos as b','bodegarticulos.Detallearticulo_id',"b.id")
            ->join('codesumis as c','b.Codesumi_id','c.id')
            ->join('lotes as d','bodegarticulos.id','d.Bodegarticulo_id')
            ->join('titulares as t','b.titular_id','t.id')
            ->leftjoin('conteoinventarios as e','d.id','e.Lote_id')
            ->leftjoin('inventario_bodegas as ib','ib.id','e.inventario_bodega_id')
            ->whereNotNull('b.codigo')
            ->where('e.inventario_bodega_id',$codigoInventario);
        if($conteos["estadoInventario"] == 31){
            $query->whereRaw('e.saldo_inicial != e.Conteo2')->whereRaw('e.Conteo1 != e.Conteo2');
        }elseif($conteos["estadoInventario"] ==30){
            $query->whereRaw('e.saldo_inicial != e.Conteo1');
        }
        $conteos["lista"] = $query->get();
        return response()->json($conteos,200);
    }

    public function inventariosIncompletos(){
        $inventarios = InventarioBodega::select([
            "b.id as bodega_id",
            "inventario_bodegas.id",
            "b.Nombre as bodega",
            "e.Nombre as estado",
            "inventario_bodegas.created_at"
        ])
            ->join('bodegas as b','b.id','inventario_bodegas.bodega_id')
            ->join('estados as e','e.id','inventario_bodegas.estado_id')
            ->where('inventario_bodegas.estado_id','!=',34)
            ->get();
        return response()->json($inventarios,200);
    }

    public function inventarios(){
        $inventarios = InventarioBodega::select([
            "b.id as bodega_id",
            "inventario_bodegas.id",
            "b.Nombre as bodega",
            "e.Nombre as estado",
            "inventario_bodegas.created_at"
        ])
            ->join('bodegas as b','b.id','inventario_bodegas.bodega_id')
            ->join('estados as e','e.id','inventario_bodegas.estado_id')
            ->get();
        return response()->json($inventarios,200);
    }

    public function detalleInventarios($codigoInventario){
        
        if(auth()->user()->hasPermissionTo('reportes.conteos.admin')){
            $query = bodegarticulo::select([
                "d.id as idLote",
                "c.Codigo as CodigoSumi",
                "b.codigo as Codigo",
                "b.Producto",
                "titulares.Nombre as Titular",
                "d.Numlote",
                "bd.Nombre as Bodega",
                "d.Fvence",
                "e.saldo_inicial",
                "e.Conteo1 as Conteo_1",
                DB::raw("(CASE WHEN e.Conteo2 is null THEN '0' ELSE e.Conteo2 END) as Conteo_2"),
                DB::raw("(CASE WHEN e.conteo3 is null THEN '0' ELSE e.conteo3 END) as Conteo_3"),
                "e.Value1 as saldo_final",
                "es.Nombre",
                "bodegarticulos.id as Bodegaarticulo_id",
                // "ppm.precio_unidad as Costo"
            ])->join('detallearticulos as b','bodegarticulos.Detallearticulo_id',"b.id")
                ->join('bodegas as bd','bodegarticulos.Bodega_id','bd.id')
                ->join('codesumis as c','b.Codesumi_id','c.id')
                ->join('lotes as d','bodegarticulos.id','d.Bodegarticulo_id')
                ->join('titulares', 'b.titular_id', 'titulares.id')
                ->leftjoin('conteoinventarios as e','d.id','e.Lote_id')
                ->leftjoin('estados as es','e.estado_id','es.id')
                ->leftjoin('inventario_bodegas as ib','ib.id','e.inventario_bodega_id')
                // ->leftjoin('precio_proveedor_medicamentos as ppm','ppm.detallearticulo_id','b.id')
                ->where('e.inventario_bodega_id',$codigoInventario)->get();
        }else{
            $query = bodegarticulo::select([
                "d.id as idLote",
                "c.Codigo as CodigoSumi",
                "b.codigo as Codigo",
                "b.Producto",
                "titulares.Nombre as Titular",
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
                ->join('titulares', 'b.titular_id', 'titulares.id')
                ->leftjoin('conteoinventarios as e','d.id','e.Lote_id')
                ->leftjoin('estados as es','e.estado_id','es.id')
                ->leftjoin('inventario_bodegas as ib','ib.id','e.inventario_bodega_id')
                // ->leftjoin('precio_proveedor_medicamentos as ppm','ppm.detallearticulo_id','b.id')
                ->where('e.inventario_bodega_id',$codigoInventario)->get();
        }
    
        return (new FastExcel($query))->download('file.xls');
    }

    public function buscarLoteEnCero(Request $request,$codigoInventario){
        $inventario = InventarioBodega::find($codigoInventario);
        $lotes = Lote::select([
            'lotes.*',
            'c.Nombre',
            'ba.bodega_id',
            'titulares.Nombre as titular',
            'd.codigo'
        ])->join('bodegarticulos as ba','lotes.Bodegarticulo_id','ba.id')
            ->join('detallearticulos as d','ba.Detallearticulo_id',"d.id")
            ->join('codesumis as c','d.Codesumi_id','c.id')
            ->join('bodegas as b','ba.Bodega_id','b.id')
            ->join('titulares', 'd.titular_id', 'titulares.id')
            ->where('b.id',$inventario->bodega_id)
            ->whereNotNull('d.codigo')
            ->where('Cantidadisponible','<=',0)
            ->whereNotIn('lotes.id',function ($query) use ($codigoInventario){
                $query->from('conteoinventarios')
                    ->select('Lote_id')
                    ->where('inventario_bodega_id',$codigoInventario);
            });
            if($request->numeroLote != ""){
                $lotes->where('lotes.Numlote',$request->numeroLote);
            }
            if($request->nombreMedicamento != ""){
                $lotes->where('c.Nombre','like','%'.strtoupper($request->nombreMedicamento).'%');
            }

        return response()->json($lotes->get(),200);
    }

    public function agregarLoteEnCero($codigoInventario,$codigoLote){
        $conteo = Conteoinventario::create([
            'Lote_id' => $codigoLote,
            'inventario_bodega_id' => $codigoInventario,
            'Estado_id' => 1,
            'saldo_inicial' => 0,
            'UserCrea_id' => auth()->id()
        ]);
        return response()->json($conteo,200);
    }

    public function buscarDetalle(Request $request){
        
        $detalles = Detallearticulo::select('detallearticulos.id', 'detallearticulos.Producto', 
        'detallearticulos.codigo', 'titulares.nombre as titular')
        ->join('titulares', 'detallearticulos.titular_id', 'titulares.id')
        ->whereNotNull('detallearticulos.codigo')
        ->where('codigo', $request->codigo)
        ->take(1)
        ->get();

        return response()->json($detalles,200);

    }

    public function agregarDetalle($codigoInventario, Request $request){

        $bodegaArticulo = bodegarticulo::create([
            'Bodega_id' => $request->bodega_id,
            'Detallearticulo_id' => $request->detalle_id,
            'Stock' => 0,
            'Cantidadmaxima' => 0,
            'Cantidadminima' => 0
        ]);

        $lote = Lote::create([
            'Numlote' => $request->lote,
            'Fvence' => $request->fecha,
            'Cantidadisponible' => 0,
            'Bodegarticulo_id' => $bodegaArticulo->id,
            'Estado_id' => 1
        ]);

        $conteo = Conteoinventario::create([
            'Lote_id' => $lote->id,
            'inventario_bodega_id' => $codigoInventario,
            'Estado_id' => 1,
            'saldo_inicial' => 0,
            'UserCrea_id' => auth()->id()
        ]);

        return response()->json($conteo,200);

    }
}
