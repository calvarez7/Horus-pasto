<?php

namespace App\Http\Controllers\Bodegas;

use App\Http\Controllers\Controller;
use App\Modelos\Auditoria;
use App\Modelos\Bodega;
use App\Modelos\bodegarticulo;
use App\Modelos\Codesumi;
use App\Modelos\Detallearticulo;
use App\Modelos\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class BodegarticuloController extends Controller
{

    public function inventariobodega(Request $request)
    {
        $inventariobodega = bodegarticulo::select([
            'bodegarticulos.Bodega_id',
            'bodegarticulos.id as Bodegarticulo_id',
	        'b.Nombre as Bodega',
	        'e.Codigo',
	        'c.Producto',
	        't.nombre as Titular',
	        'c.CUM_completo',
	        'e.Estado_id',
	        'd.id as Lote_id',
	        'd.Numlote',
	        'd.Fvence',
            'bodegarticulos.Stock',
            'd.Cantidadisponible',
            'c.codigo as codigoDetalle',
            'e.Nombre as nombreSumi',
            DB::raw('(select TOP 1 precio_unidad from precio_proveedor_medicamentos where precio_proveedor_medicamentos.detallearticulo_id = c.id) as precio_unidad') ,
        ])->join('bodegas as b',function ($join){
            $join->on('bodegarticulos.Bodega_id', '=', 'b.id');
            $join->on('b.Estado_id', '=', DB::raw("1"));
        })
            ->join('detallearticulos as c', 'bodegarticulos.Detallearticulo_id', 'c.id')
            ->join('lotes as d','bodegarticulos.id','d.Bodegarticulo_id')
            ->leftjoin('titulares as t','t.id','c.titular_id')
            ->join('codesumis as e','c.Codesumi_id','e.id');
        if($request->Bodega_id){
            $inventariobodega->where('bodegarticulos.Bodega_id',$request->Bodega_id);
        }
        $inventariobodega->where('d.Cantidadisponible','>',0)
            ->orderBy('c.Producto', 'desc');
        $data = $inventariobodega->get();
        return response()->json($data, 200);
    }

    public function inventarioOtherStock(Request $request)
    {
        $bodegarticulo = bodegarticulo::find($request->Bodegarticulo_id);
        $stocks        = bodegarticulo::select(['b.Nombre', 'bodegarticulos.Stock'])
            ->join('bodegas as b', 'bodegarticulos.Bodega_id', 'b.id')
            ->where('bodegarticulos.Detallearticulo_id', $bodegarticulo->Detallearticulo_id)
            ->get();

        return response()->json($stocks, 200);
    }

    public function all(Bodega $bodega, Request $request)
    {
        $codesumis = Codesumi::select(['codesumis.*'])
            ->join('detallearticulos as da', 'da.Codesumi_id', 'codesumis.id')
            ->join('bodegarticulos as ba', 'ba.Detallearticulo_id', 'da.id')
            ->with(['detallearticulos' => function ($query) use ($bodega) {
                $query->select('detallearticulos.id', 'detallearticulos.Codesumi_id', 'detallearticulos.CUM_completo', 'detallearticulos.Titular')
                    ->where('detallearticulos.Estado_id', 1)
                    ->get();
            }])
            ->whereHas('detallearticulos', function ($query) use ($bodega) {
                $query->where('Estado_id', 1);
            })
            ->whereNotNull('codesumis.Codigo')
            ->whereNotNull('codesumis.Frecuencia')
            ->whereNotNull('codesumis.Cantidadmaxordenar')
            ->whereNotNull('codesumis.Requiere_autorizacion')
            ->whereNotNull('codesumis.Nivel_Ordenamiento')
        // ->where('da.Activo_HORUS','SI')
            ->where('ba.Bodega_id', $bodega->id)
            ->where('codesumis.Estado_id', 1)
            ->where('codesumis.Nombre', 'LIKE', '%' . $request->nombre . '%')
            ->orWhere('codesumis.Codigo', 'LIKE', '%' . $request->nombre . '%')
            ->get();

        return response()->json($codesumis, 200);
    }

    public function allWithoutBodega(Request $request)
    {
        $detalleArticulo = Detallearticulo::select(
            'detallearticulos.id',
            DB::raw("CONCAT(detallearticulos.codigo,' (',c.Nombre,'-',t.nombre,')') AS Nombre") ,
            'detallearticulos.Producto',
            'bd.Stock',
            'bd.Stock as Cantidadtotal',
            'detallearticulos.codigo as Codigo',
            'detallearticulos.Codesumi_id',
        )
            ->join('codesumis as c','c.id','detallearticulos.Codesumi_id')
            ->join('bodegarticulos as bd','bd.Detallearticulo_id','detallearticulos.id')
            ->join('titulares as t','t.id','detallearticulos.titular_id')
            ->whereNotNull('detallearticulos.codigo')
            ->where(DB::raw("CONCAT(detallearticulos.codigo,'-',c.Nombre)"), 'LIKE', '%' . $request->article . '%')
            ->where('bd.Bodega_id',$request["bodega"])
            ->where('c.Estado_id', 1)->distinct()->get();

        return response()->json($detalleArticulo);
    }

    public function allArticulos(Bodega $bodega, Request $request)
    {
        $codesumis = Codesumi::select(['codesumis.', 'da.'])
            ->join('detallearticulos as da', 'da.Codesumi_id', 'codesumis.id')
            ->join('bodegarticulos as ba', 'ba.Detallearticulo_id', 'da.id')
            ->whereNotNull('codesumis.Codigo')
            ->whereNotNull('codesumis.Frecuencia')
            ->whereNotNull('codesumis.Cantidadmaxordenar')
            ->whereNotNull('codesumis.Requiere_autorizacion')
            ->whereNotNull('codesumis.Nivel_Ordenamiento')
            ->where('da.Activo_HORUS', 'SI')
            ->where('ba.Bodega_id', $bodega->id)
            ->where('codesumis.Nombre', 'LIKE', '%' . $request->nombre . '%')
            ->where('codesumis.Estado_id', 1)
            ->distinct()
            ->count();
        return response()->json($codesumis, 200);
    }

    public function store(Request $request)
    {
        //
        $input = $request->all();
        bodegarticulo::create($input);
        return response()->json([
            'message' => 'Bodegarticulo fue creado con exito!',
        ], 201);
    }

    public function update(Request $request, Bodegarticulo $bodega, Detallearticulo $detallearticulo)
    {
        $bodega->update([
            'Stock'          => $request->Stock,
            'Cantidadmaxima' => $request->Cantidadmaxima,
            'Cantidadminima' => $request->Cantidadminima,

        ]);
        return response()->json([
            'message' => 'Bodegarticulo actualizado con exito!',
        ]);
    }

    public function updateCant(Request $request)
    {

        $lote = Lote::find($request->Lote_id);

        $Bodegarticulo = Bodegarticulo::find($request->Bodegarticulo_id);

        $cantBefore = $lote->Cantidadisponible;

        $Bodegarticulo->update([
            'Stock' => ($Bodegarticulo->Stock - $lote->Cantidadisponible) + $request->Cantidadisponible,
        ]);

        $lote->update([
            'Cantidadisponible' => $request->Cantidadisponible,
        ]);

        $cantAfter = $lote->Cantidadisponible;

        $msg = 'Se modifica la cantidad de ' . $cantBefore . ' a ' . $cantAfter . ' para el lote ' . $lote->id . '';

        Auditoria::create([
            'Descripcion'        => $msg,
            'Tabla'              => 'Lotes',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $lote->id,
            'Motivo'             => $request->observaciones,
        ]);

        return response()->json([
            'message' => 'Existencias actualizadas con exito!',
        ]);
    }
    public function loteporBodega(Request $request)
    {
        $loteporBodega = DB::select("exec dbo.LoteporMedicamento" . " $request->Bodega_id");
        return response()->json($loteporBodega, 200);
    }

    public function faltaconteo1(Request $request)
    {
        $Faltaconteo1 = DB::select("exec dbo.Faltaconteo1" . " $request->Bodega_id");
        return response()->json($Faltaconteo1, 200);
    }

    public function faltaconteo2(Request $request)
    {
        $Faltaconteo2 = DB::select("exec dbo.Faltaconteo2" . " $request->Bodega_id");
        return response()->json($Faltaconteo2, 200);
    }

    public function updateLote(Lote $lote,Request $request){
    $result = $lote->update($request->all());
        return response()->json($result, 200);
    }

    public function getDetallesArticulos(Request $request){
    $detalleArticulos = Detallearticulo::select(['detallearticulos.*','t.nombre as Titular'])
        ->join('titulares as t','t.id','detallearticulos.titular_id')
        ->join('codesumis as c','c.id','detallearticulos.Codesumi_id')
        ->where('detallearticulos.Estado_id', 1)
        ->where('c.Estado_id', 1)
        ->whereNotNull('detallearticulos.unidad_compra')
        ->whereNotNull('detallearticulos.codigo')
        ->get();


        return response()->json($detalleArticulos, 200);

    }

    public function getMedicamentos(Request $request){
        $codesumis = Codesumi::select(['codesumis.*'])
            ->join('detallearticulos as da', 'da.Codesumi_id', 'codesumis.id')
            ->with(['detallearticulos' => function ($query) {
                $query->select('detallearticulos.id', 'detallearticulos.Codesumi_id', 'detallearticulos.CUM_completo', 't.nombre as Titular',
                'detallearticulos.precio_unidad', 'detallearticulos.unidad_compra')
                    ->join('titulares as t','t.id','detallearticulos.titular_id')
                    ->where('detallearticulos.Estado_id', 1)
                    ->whereNotNull('detallearticulos.unidad_compra')
                    ->get();
            }])
            ->whereNotNull('codesumis.Codigo')
            ->whereNotNull('codesumis.Frecuencia')
            ->whereNotNull('codesumis.Cantidadmaxordenar')
            ->whereNotNull('codesumis.Requiere_autorizacion')
            ->whereNotNull('codesumis.Nivel_Ordenamiento')
            ->where('codesumis.Estado_id', 1)
            ->where('codesumis.Nombre', 'LIKE', '%' . $request->nombre . '%')
            ->orWhere('codesumis.Codigo', 'LIKE', '%' . $request->nombre . '%')
            ->get();

        return response()->json($codesumis, 200);

    }

    public function cargaMasiva(Request $request){
        $noRegistra = [];
        $registra = [];
        (new FastExcel)->import($request->data, function ($line) use (&$noRegistra,&$registra){
            if($line["CODIGO"]){
                $detalleArticulo =  Detallearticulo::select([
                    'detallearticulos.id',
                    'detallearticulos.codigo as Codigo',
                    'c.Nombre',
                    't.nombre as Titular',
                    'detallearticulos.unidad_compra'
                ])
                    ->join('codesumis as c','c.id','detallearticulos.Codesumi_id')
                    ->join('titulares as t','t.id','detallearticulos.titular_id')
                    ->where('detallearticulos.codigo',$line["CODIGO"])->first();
                if($detalleArticulo){
//                    Cantidadtotal
                    $registra[] = ['bodegaArticulo' => $detalleArticulo->toArray(),'Cantidadtotal'=> $line["CANTIDAD"]];
                }else{
                    $noRegistra[] = $line["CODIGO"];
                }
            }
        });
        $data = [
            'medicamentos' => $registra,
            'errores' => $noRegistra
        ];
        return response()->json($data, 200);
    }

    public function getLotesDetalleArticulo($bodega ,$detalleArticulo){
        $lotes = Lote::select('lotes.*')
            ->join('bodegarticulos as ba', 'ba.id', 'lotes.Bodegarticulo_id')
            ->join('detallearticulos as da', 'da.id', 'ba.Detallearticulo_id')
            ->where('da.id', $detalleArticulo)
            ->where('ba.Bodega_id', $bodega)
            ->where('lotes.Cantidadisponible', '>', 0)
            ->get();

        return response()->json($lotes);
    }

    public function findloteAjuste(Request $request){
        $lote = Lote::select(['d.id','lotes.cantidadisponible','lotes.Fvence','d.presentacion_comercial','lotes.Numlote','b.id as bodegaArticulo'])
            ->join('bodegarticulos as b','lotes.Bodegarticulo_id','b.id')
            ->join('detallearticulos as d','b.Detallearticulo_id','d.id')
            ->where('b.Bodega_id',intval($request->bodega))
            ->where('b.Detallearticulo_id',intval($request->id))
            ->where('lotes.Numlote',$request->lote)
            ->first();
        if($lote){
            $data = $lote->toArray();
            $data["Codigo"] = $request->Codigo;
            $data["Nombre"] = $request->Nombre;
            $data["cantidad"] = $request->cantidad;

            return response()->json($data);
        }else{
            if($request->tipoSolicitud ===  6){
                $data = [];
                $data["id"] = $request->id;
                $data["Codigo"] = $request->Codigo;
                $data["Nombre"] = $request->Nombre;
                $data["cantidad"] = $request->cantidad;
                $data["Numlote"] = $request->lote;
                $data["Fvence"] = $request->fVence;
                return response()->json($data);
            }else{
                return response()->json(["message" => "Lote no registra en la bodega"]);
            }
        }
    }

    public function allMedicamentos(Request $request)
    {
        $detalleArticulo = Detallearticulo::select(
            'detallearticulos.id','c.Nombre','detallearticulos.Via_Administracion', 'detallearticulos.Forma_Farmaceutica'
        )
            ->join('codesumis as c','c.id','detallearticulos.Codesumi_id')
            ->join('bodegarticulos as bd','bd.Detallearticulo_id','detallearticulos.id')
            ->join('titulares as t','t.id','detallearticulos.titular_id')
            ->whereNotNull('detallearticulos.codigo')
            ->where(DB::raw("CONCAT(detallearticulos.codigo,'-',c.Nombre)"), 'LIKE', '%' . $request->article . '%')
            ->where('c.Estado_id', 1)->distinct()->get();

        return response()->json($detalleArticulo);
    }

    public function detallesCodesumisReposicion($codigo,$bodega){
        $detalles = Detallearticulo::select([
            'detallearticulos.id',
            'c.Nombre',
            'detallearticulos.Producto',
            'detallearticulos.codigo',
            't.nombre as titular',
            'bd.Stock'
        ])
            ->join('codesumis as c','c.id','detallearticulos.Codesumi_id')
            ->join('bodegarticulos as bd','bd.Detallearticulo_id','detallearticulos.id')
            ->join('titulares as t','t.id','detallearticulos.titular_id')
            ->where('c.Codigo',$codigo)
            ->where('bd.bodega_id',$bodega)
            ->get();

        return response()->json($detalles);
    }
}
