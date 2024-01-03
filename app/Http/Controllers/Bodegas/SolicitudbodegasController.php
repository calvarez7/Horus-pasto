<?php

namespace App\Http\Controllers\Bodegas;

use App\Http\Controllers\Controller;
use App\Modelos\Auditoria;
use App\Modelos\Bodega;
use App\Modelos\DetalleSolicitudBodega;
use App\Modelos\Solicitudbodegas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudbodegasController extends Controller
{
    public function getSolicitudByBodegaDestino(Bodega $bodega)
    {
        $solicitud = Solicitudbodegas::select(['dsb.id',
            'dsb.id as detalle_id',
            'dsb.Cantidad',
            'dsb.Codesumi_id',
            'dsb.detallearticulo_id',
            'Solicitudbodegas.Bodegadestino_id',
            'Solicitudbodegas.Usuariosolicita_id',
            'Solicitudbodegas.Estado_id',
            'Solicitudbodegas.created_at',
            'Solicitudbodegas.updated_at',
            DB::raw("CONCAT(c.Nombre,' (',t.nombre,')' )as Medicamento"),
            'd.Codigo as Codigo_Medicamento',
            'b.Nombre as Nombre_BodegaDestino'])
            ->join('detalle_solicitud_bodega as dsb', 'dsb.solicitudBodega_id', 'Solicitudbodegas.id')
            ->join('detallearticulos as d','d.id','dsb.detallearticulo_id')
            ->join('titulares as t','t.id','d.titular_id')
            ->join('Codesumis as c', 'd.Codesumi_id', 'c.id')
            ->join('Bodegas as b', 'Solicitudbodegas.Bodegadestino_id', 'b.id')
            ->where('Solicitudbodegas.Bodegaorigen_id', $bodega->id)
            ->where('dsb.Estado_id', 1)
            ->get();

        return response()->json($solicitud, 200);
    }

    public function store(Request $request)
    {
        $solicitud = Solicitudbodegas::create([
            'Bodegaorigen_id'    => $request->solicitudes[0]['Bodegaorigen_id'],
            'Usuariosolicita_id' => auth()->user()->id,
            'Bodegadestino_id'   => $request->solicitudes[0]['Bodegadestino_id'],
            'Tipotransacion_id'  => 2,
            'Estado_id'          => 1,
        ]);

        foreach ($request->solicitudes as $articulo) {
            $detalle              = new DetalleSolicitudBodega;
            $detalle->Cantidad    = intval($articulo['Cantidad']);
            $detalle->Codesumi_id = $articulo['Codesumi_id'];
            $detalle->detallearticulo_id = $articulo["detalleArticulo"];
            $solicitud->detalles()->save($detalle);
        }

        return response()->json([
            'message'   => 'Solicitud de traslado creada con exito!',
            'solicitud' => $solicitud->id,
        ], 201);
    }

    // public function store(Request $request)
    // {
    //     $solicitud = Solicitudbodegas::create([
    //         'Bodegaorigen_id' => $request->solicitudes[0]['Bodegaorigen_id'],
    //         'Usuariosolicita_id' => auth()->user()->id,
    //         'Bodegadestino_id' => $request->solicitudes[0]['Bodegadestino_id'],
    //         'Tipotransacion_id' => 2,
    //         'Estado_id' => 1,
    //     ]);

    //     foreach ($request->solicitudes as $articulo){
    //         $detalle = new DetalleSolicitudBodega;
    //         $detalle->Bodegarticulo_id = $articulo['Titular']['Bodegarticulo_id'];
    //         $detalle->Lote = $articulo['lote'];
    //         $detalle->Cantidad = $articulo['Cantidadtotal'];
    //         $detalle->Codesumi_id = $articulo['Codesumi_id'];
    //         $solicitud->detalles()->save($detalle);
    //     }

    //     return response()->json([
    //         'message' => 'Solicitud de traslado creada con exito!'
    //     ], 201);
    // }

    public function cancelTransfer($solicitud, Request $request)
    {
        $detalle = DetalleSolicitudBodega::find($solicitud);
        if($detalle) {
            $msg = 'Se anula la solicitud ' . $detalle->SolicitudBodega_id;
            Auditoria::create([
                'Descripcion' => $msg,
                'Tabla' => 'Solicitudbodegas',
                'Usuariomodifica_id' => auth()->user()->id,
                'Model_id' => $detalle->SolicitudBodega_id,
                'Motivo' => $request->observaciones,
            ]);
            $detalle->update([
                'Estado_id' => 6,
            ]);
        }
        return response()->json([
            'message' => 'Solicitud cancelada de forma exitosa!',
        ], 200);
    }

    public function trasladosAprobados(Bodega $bodega)
    {
        $solicitud = Solicitudbodegas::select(['Solicitudbodegas.id',
            'dsb.id as detalle_id',
            'dsb.Cantidad',
            'dsb.Codesumi_id',
            'Solicitudbodegas.Bodegaorigen_id',
            'Solicitudbodegas.Bodegadestino_id',
            'Solicitudbodegas.Usuariosolicita_id',
            'Solicitudbodegas.Estado_id',
            'Solicitudbodegas.created_at',
            'Solicitudbodegas.updated_at',
            'c.Nombre as Medicamento',
            'c.Codigo as Codigo_Medicamento',
            'b.Nombre as Nombre_BodegaOrigen'])
            ->join('detalle_solicitud_bodega as dsb', 'dsb.solicitudBodega_id', 'Solicitudbodegas.id')
            ->join('Codesumis as c', 'dsb.Codesumi_id', 'c.id')
            ->join('Bodegas as b', 'Solicitudbodegas.Bodegadestino_id', 'b.id')
            ->where('Solicitudbodegas.Bodegaorigen_id', $bodega->id)
            ->where('dsb.Estado_id', 1)
            ->get();

        return response()->json($solicitud, 200);
    }
}
