<?php

namespace App\Http\Controllers\Transaciones;

use App\Http\Controllers\Controller;
use App\Modelos\Auditoria;
use App\Modelos\Bodega;
use App\Modelos\bodegarticulo;
use App\Modelos\Bodegatransacion;
use App\Modelos\Codesumi;
use App\Modelos\Detaarticulorden;
use App\Modelos\Detallearticulo;
use App\Modelos\DetalleSolicitudBodega;
use App\Modelos\HistoricoPrecioProveedorMedicamento;
use App\Modelos\Lote;
use App\Modelos\Movimiento;
use App\Modelos\Notastransacion;
use App\Modelos\NovedadesOrdenCompra;
use App\Modelos\Orden;
use App\Modelos\Ordencompra;
use App\Modelos\PrecioProveedorMedicamento;
use App\Modelos\Solicitudbodegas;
use App\Modelos\SolicitudCompra;
use App\Modelos\Tipotransacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class MovimientoController extends Controller
{
    private $_index           = 1;
    private $_inconsistencias = [];

    public function all(Bodega $bodega)
    {
        $movimiento = Movimiento::select(['Movimientos.*', 'Transacions.Nombre as NombreTransacion', 'Tipos.Nombre as NomTipotransacion',
            'Users.name as NomusuarioCrea', 'Estados.Nombre as NomEstado', 'Reps.Nombre as NomReps',
            'BodegaOrigen.Nombre as BodegaOrigen', 'BodegaDestino.Nombre as BodegaDestino', 'Prestadores.Nombre as NomPrestador'])
            ->join('Tipotransacions', 'Movimientos.Tipotransacion_id', 'Tipotransacions.id')
            ->join('Transacions', 'Tipotransacions.Transacion_id', 'Transacions.id')
            ->join('Tipos', 'Tipotransacions.Tipo_id', 'Tipos.id')
            ->leftJoin('Reps', 'Movimientos.Reps_id', 'Reps.id')
            ->join('Users', 'Movimientos.usuario_id', 'Users.id')
            ->join('Estados', 'Movimientos.Estado_id', 'Estados.id')
            ->leftJoin('Bodegas as BodegaOrigen', 'Movimientos.BodegaOrigen_id', 'BodegaOrigen.id')
            ->leftJoin('Bodegas as BodegaDestino', 'Movimientos.BodegaDestino_id', 'BodegaDestino.id')
            ->leftJoin('Prestadores', 'Movimientos.Prestador_id', 'Prestadores.id')
            ->get();
        return response()->json($movimiento, 200);
    }

    public function dispensar(Request $request)
    {
        $ultimoMovimiento = Movimiento::select(['Orden_id', 'created_at'])->where('Orden_id', $request->medicamentos[0]['Orden_id'])->orderBy('id', 'desc')->first();
        $minutos = 1;
        if($ultimoMovimiento){
            $minutos = (strtotime($ultimoMovimiento->created_at) - strtotime(date('Y-m-d H:i:s'))) / 60;
            $minutos = abs($minutos);
            $minutos = floor($minutos);
        }
        $condicion = $request->medicamentos[0]['condicion'];
        $bandera = 0;
        foreach ($request->medicamentos as $medic) {
            $sum = 0;
            foreach ($medic['lotes'] as $lote) {
                $sum += $lote['cantidad'];
            }
            $detalle = Detaarticulorden::find($medic['id']);
            if ($detalle->Cantidadmensualdisponible < $sum) {
                $bandera = 1;
             }
        }
        if ($minutos >= 1 && $bandera == 0) {
        $movimiento = Movimiento::create([
            'Tipotransacion_id' => 3,
            'Orden_id' => $request->medicamentos[0]['Orden_id'],
            'usuario_id' => auth()->user()->id,
            'created_at' => $request->Fechadispensacion,
            'BodegaOrigen_id' => $request->bodega_id,
        ]);

        foreach ($request->medicamentos as $medicamento) {
            if (count($medicamento['lotes']) > 0) {
                if (intval($medicamento['Cantidadmensualdisponible']) <= 0) {
                    continue;
                }
                $sum = 0;
                foreach ($medicamento['lotes'] as $lote) {
                    if (intval($lote['cantidad']) > 0) {
                        $cantidad = $lote['cantidad'];
                        $aprovechamientoRestante = 0;
                        $aprovechamientoUtilizado = 0;
                        $lot = Lote::find($lote['id']);
                        $aprovechamiento = $lot->aprovechamiento;
                        if ($medicamento['tipoOrden'] == 7) {
                            if (($medicamento['concentracion'])) {
                                $flag = true;
                                $i = 0;
                                while ($flag) {
                                    if ($aprovechamiento >= $lote['cantidad']) {
                                        $aprovechamientoRestante = $aprovechamiento - $lote['cantidad'];
                                        $aprovechamientoUtilizado = $lote['cantidad'];
                                        $flag = false;
                                    } else {
                                        if ((($medicamento['concentracion'] * $i) + $aprovechamiento) < $lote['cantidad']) {
                                            $i++;
                                        } else {
                                            $aprovechamientoRestante = (($medicamento['concentracion'] * $i) + $aprovechamiento) - $lote['cantidad'];
                                            $aprovechamientoUtilizado = $lote['cantidad'] - ($medicamento['concentracion'] * $i);
                                            $flag = false;
                                        }
                                    }
                                }
                                $cantidad = $i;
                            }
                        }
                        $bodegarticulo = bodegarticulo::find($lot->Bodegarticulo_id);
                        $bodegarticulo->update([
                            'Stock' => $bodegarticulo->Stock - $cantidad,
                        ]);
                        if ($medicamento['tipoOrden'] == 7) {
                            $sum += $lote['cantidad'];
                        } else {
                            $sum += $cantidad;
                        }
                        $lot->update([
                            'Cantidadisponible' => $lot->Cantidadisponible - $cantidad,
                            'aprovechamiento' => $aprovechamientoRestante,
                        ]);
                        $bodegatransacion = Bodegatransacion::where('Bodegarticulo_id', $bodegarticulo->id)->orderBy('id', 'DESC')->first();
                        if (!isset($bodegatransacion)) {
                            $precio = 0;
                            $valorTra = 0;
                            $valorTotal = 0;
                            $valorProm = 0;
                        } else {
                            $precio = round($bodegatransacion->Valorpromedio);
                            $valorTra = $precio * $cantidad;
                            $valorTotal = $bodegatransacion->Valortotal - $valorTra;
                            if ($bodegarticulo->Stock != 0) {
                                $valorProm = round($valorTotal / $bodegarticulo->Stock, 2);
                            } else {
                                $valorProm = 0;
                            }

                        }

                        Bodegatransacion::create([
                            'Lote_id' => $lot->id,
                            'Movimiento_id' => $movimiento->id,
                            'Cantidadtotal' => $lote['pEntregar'], //cantidad real que debería ser movida en el inventario
                            'CantidadtotalFactura' => $cantidad, //cantidad que se esta moviendo
                            'Precio' => $precio,
                            'Cantidadtotalinventario' => $bodegarticulo->Stock,
                            'Valor' => $valorTra,
                            'Valortotal' => $valorTotal,
                            'Valorpromedio' => $valorProm,
                            'Estado_id' => 1,
                            'Bodegarticulo_id' => $bodegarticulo->id,
                            'created_at' => $request->Fechadispensacion,
                        ]);
                    }
                }
                if (intval($sum) > 0) {
                    $mediOrden = Detaarticulorden::find($medicamento['id']);
                    if ($mediOrden->Cantidadmensualdisponible == $sum) {
                        $mediOrden->update([
                            'Estado_id' => 17,
                            'Cantidadmensualdisponible' => 0,
                            'adomicilio' => $request->domicilio,
                            'domiciliario' => $request->namedomiciliario,
                        ]);
                    } else {
                        $mediOrden->update([
                            'Estado_id' => 19,
                            'Cantidadmensualdisponible' => $mediOrden->Cantidadmensualdisponible - $sum,
                            'adomicilio' => $request->domicilio,
                            'domiciliario' => $request->namedomiciliario,
                            'fecha_pendiente' => date("Y-m-d H:i:s")
                        ]);
                    }

                    $orden = Orden::select(['ordens.*', 'to.Nombre as tipo_orden'])
                        ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
                        ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                        ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
                        ->join('detaarticulordens as do', 'do.Orden_id', 'ordens.id')
                        ->with(['detaarticulordens' => function ($query) {
                            $query->select('detaarticulordens.*', 'cs.Codigo as Codigo', 'cs.Nombre as medicamento')
                                ->join('codesumis as cs', 'detaarticulordens.Codesumi_id', 'cs.id')
                                ->whereIn('detaarticulordens.Estado_id', [1, 7, 18, 19])
                                ->get();
                        }])
                        ->whereHas('detaarticulordens', function ($query) {
                            $query->whereIn('Estado_id', [1, 7, 18, 19]);
                        })
                        ->distinct()
                        ->where('ordens.id', $mediOrden->Orden_id)
                        ->first();
                    if (!$orden) {
                        $orden = Orden::find($mediOrden->Orden_id);
                        $orden->update([
                            'Estado_id' => 17,
                        ]);
                            if ($condicion === 'libre') {
                                $this->pagination($orden, $request->Fechadispesacion);
                            }
                    }
                } else {
                    return response()->json([
                        'message' => 'Error al dispensar, por favor intente nuevamente',
                    ], 201);
                }
            } else {
                if (isset($medicamento['color']) && $medicamento['color'] === 'lightcoral')
                    Detaarticulorden::where('id', $medicamento['id'])->update(['Estado_id' => 18]);
            }
        }
    }else{
            return response()->json([
                'message' => 'Error al dispensar, numero de movimientos excedido, tiempo de espera: 1 minuto',
            ], 201);
    }


return response()->json([
            'message' => 'Dispensación realizada con exito!',
        ], 201);
    }

    private function pagination($orden, $fechadispensacion)
    {
        if (isset($orden->paginacion)) {
            $pagination = explode('/', $orden->paginacion);

            if (isset($pagination[1])) {
                $j = intval($pagination[0]) + 1;
                for ($i = $j; $i <= intval($pagination[1]); $i++) {
                    $date = date('Y-m-d', strtotime($fechadispensacion . "+" . strval(($i - intval($pagination[0])) * 28) . " day"));
                    $o    = Orden::where('paginacion', $i . "/" . $pagination[1])->where('Cita_id', $orden->Cita_id)->where('autorizacion',$orden->autorizacion)->first();
                    $o->update(['Fechaorden' => $date]);
                }
            }
        }
    }

    public function store(Request $request, Bodega $bodega)
    {

        /*$Numfacturazeus = isset($request->Numfacturazeus) ? $request->Numfacturazeus : 'N/A';*/
        /*$Numfactura = isset($request->Numfactura) ? $request->Numfactura : 'N/A';*/

        $movimiento = Movimiento::create([
            'Tipotransacion_id' => $request->Tipotransacion_id,
            //'Numfacturazeus' => $Numfacturazeus,
            //'Numfactura' => $Numfactura,
            'prestador_id'      => $request->prestador_id,
            'BodegaOrigen_id'   => $request->BodegaOrigen_id,
            'BodegaDestino_id'  => $request->BodegaDestino_id,
            'Reps_id'           => $request->Reps_id,
            'usuario_id'        => auth()->user()->id,
            'Bodegadestino_id'  => $request->Bodegadestino_id,
            'Estado_id'         => 15,

        ]);

        foreach ($request->bodegarticulos as $bodegarticulo) {

            $stock = bodegarticulo::select('Stock', 'id')
                ->where('id', $bodegarticulo['Bodegarticulo_id'])
                ->first();

            $valoranterior = Bodegatransacion::select('Valortotal', 'Valor')
                ->where('Bodegarticulo_id', $bodegarticulo['Bodegarticulo_id'])
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->first();
            $tipotansacion1 = Tipotransacion::select('id')
                ->where('Tipotransacions.id', $request->Tipotransacion_id)
                ->first();

            if ($tipotansacion1->id == '2') {
                $valor  = -$bodegarticulo['Cantidadtotal'] * $bodegarticulo['Precio'];
                $salida = -$bodegarticulo['Cantidadtotal'];

            } else {
                $valor  = $bodegarticulo['Cantidadtotal'] * $bodegarticulo['Precio'];
                $salida = $bodegarticulo['Cantidadtotal'];
                //$facturaEntrada = isset($bodegarticulo['CantidadtotalFactura'])? $bodegarticulo['CantidadtotalFactura']  :'';
            }

            if (isset($valoranterior)) {
                $valortotal = $valoranterior->Valortotal + $salida * $bodegarticulo['Precio'];
            } else {
                $valortotal = $salida * $bodegarticulo['Precio'];
            }

            /*if (isset($bodegarticulo['Fvence'])) {
            try {
            $date = new \DateTime($bodegarticulo['Fvence']);
            $date_format = $date->format('Y-m-d');
            } catch (Exception $e) {
            $date_format = "2099-01-01";
            }

            } else {
            $date_format = "2099-01-01";
            }*/

            $bodega_guerdada = Bodegatransacion::create([
                'Movimiento_id'           => $movimiento->id,
                'Cantidadtotal'           => $salida,
                'Precio'                  => $bodegarticulo['Precio'],
                'Bodegarticulo_id'        => 1,
                'Cantidadtotalinventario' => $stock->Stock + $salida,
                'Valor'                   => $valor,
                'Valortotal'              => $valortotal,
                'CantidadtotalFactura'    => 0,
                'Estado_id'               => 15,
            ]);

            $bodega_guerdada->update([
                'Bodegarticulo_id' => 1,
            ]);

            /*$prueba = Bodegatransacion::all()->last();
        $promedio = $prueba->Valortotal / $prueba->Cantidadtotalinventario;

        $bodegatransacion = Bodegatransacion::where('id', $prueba->id)->first();
        if (isset($bodegatransacion)){
        $bodegatransacion->Valorpromedio = $promedio;
        $bodegatransacion->save();
        }*/

        }

        return response()->json([
            'message' => 'Movimiento con exito!',
        ], 201);

    }

    public function entradaFactura(Request $request, Bodega $bodega)
    {
        $movimiento = Movimiento::create([
            'Tipotransacion_id' => 4,
            'Numfacturazeus'    => $request->Numfacturazeus,
            'Numfactura'        => $request->Numfactura,
            'Estado_id'         => 1,
            'usuario_id'        => auth()->user()->id,
            'BodegaOrigen_id'   => $bodega->id,
        ]);
        foreach ($request->selected as $item) {
            $articulo = bodegarticulo::find($item['bodegaarticulo_id']);
            $lote     = Lote::where('Numlote', $item['Lote'])->where('Bodegarticulo_id', $articulo->id)->first();
            if (!isset($lote)) {
                $lote = Lote::create([
                    'Numlote'           => $item['Lote'],
                    'Fvence'            => $item['Fvence'],
                    'Cantidadisponible' => 0,
                    'Bodegarticulo_id'  => $articulo->id,
                    'Estado_id'         => 1,
                ]);
            }

            $bodegatransacion = Bodegatransacion::where('Bodegarticulo_id', $articulo->id)->orderBy('id', 'DESC')->first();
            if (!isset($bodegatransacion)) {
                $valorAnt = 0;
            } else {
                $valorAnt = $bodegatransacion->Valortotal;
            }
            $valor             = $item['Precio'] * $item['CantidadtotalFactura'];
            $valortotal        = $valor + $valorAnt;
            $cantidadInvetario = $articulo->Stock + $item['CantidadtotalFactura'];
            $valorprom         = $valortotal / $cantidadInvetario;
            Bodegatransacion::create([
                'Lote_id'                 => $lote->id,
                'Movimiento_id'           => $movimiento->id,
                'Cantidadtotal'           => $item['Cantidad'],
                'CantidadtotalFactura'    => $item['CantidadtotalFactura'],
                'Cantidadtotalinventario' => $cantidadInvetario,
                'Precio'                  => $item['Precio'],
                'Valor'                   => $valor,
                'Valortotal'              => $valortotal,
                'Valorpromedio'           => $valorprom,
                'Estado_id'               => 1,
                'Bodegarticulo_id'        => $articulo->id,
            ]);

            $ordencompra = Ordencompra::find($item['id']);
            $diff        = $ordencompra->Cantidad - $item['CantidadtotalFactura'];
            if ($diff == 0) {
                $state = 2;
            } else {
                $state = 7;
            }
            $ordencompra->update([
                'Cantidad'  => $diff,
                'Estado_id' => $state,
            ]);

            $lote->update([
                'Cantidadisponible' => $lote->Cantidadisponible + $item['CantidadtotalFactura'],
            ]);

            $articulo->update([
                'Stock' => $articulo->Stock + $item['CantidadtotalFactura'],
            ]);
        }
        return response()->json([
            'message' => '¡Factura ingresada con exito!',
        ], 200);
    }

    public function generateTrasladoSalida(Request $request)
    {
        $mensaje = '¡Traslado aceptado!';
        $status = 200;

        foreach ($request->all() as $traslado) {
            $detalleSolicitud = DetalleSolicitudBodega::find($traslado['Solicitud_id']);
            $solicitud = Solicitudbodegas::find($detalleSolicitud->SolicitudBodega_id);
            $detalle   = DetalleSolicitudBodega::find($traslado['detalle']);

            foreach ($traslado['Lote'] as $l){
                $lote = Lote::find($l['id']);
                $bodegarticulo = bodegarticulo::find($l['bodegaArticulo']);
                if(intval($lote->Cantidadisponible) < intval($l['cantidadTraslado']) || intval($bodegarticulo->Stock) < intval($l['cantidadTraslado'])){
                    $mensaje = "La cantidad solicitada sobrepasa la cantidad disponible";
                    $status = 401;
                }else{
                    $cantidadLote = intval($lote->Cantidadisponible);
                    $cantidadArticulo = intval($bodegarticulo->Stock);
                    $movimiento = Movimiento::create([
                        'Tipotransacion_id' => 2,
                        'Estado_id'         => 1,
                        'usuario_id'        => auth()->user()->id,
                        'BodegaDestino_id'  => $request->BodegaDestino_id,
                        'BodegaOrigen_id'   => $request->BodegaOrigen_id,
                    ]);
                    $lote->update([
                        'Cantidadisponible' => $cantidadLote - abs(intval($l['cantidadTraslado'])),
                    ]);
                    $bodegarticulo->update([
                        'Stock' => $cantidadArticulo - abs(intval($l['cantidadTraslado'])),
                    ]);

                    $detalle->update([
                        'cantidad_aprobada'  => $traslado['Cantidad'],
                        'Estado_id' => 7,
                        'lote_referencia_id' => $lote->id
                    ]);
                    Bodegatransacion::create([
                        'Lote_id'                 => $l["id"],
                        'Movimiento_id'           => $movimiento->id,
                        'Solicitud_id'            => $solicitud->id,
                        'Cantidadtotal'           => $traslado['CantidadTotal'], //cantidad real que debería ser movida en el inventario
                        'CantidadtotalFactura'    => $l['cantidadTraslado'], //cantidad que se esta moviendo
                        'Cantidadtotalinventario' => $bodegarticulo->Stock - $l['cantidadTraslado'],
                        'Precio'                  => 0,
                        'Valor'                   => 0,
                        'Valortotal'              => 0,
                        'Valorpromedio'           => 0,
                        'Estado_id'               => 4,
                        'Bodegarticulo_id'        => $bodegarticulo->id,
                    ]);
                }


            }
        }

        return response()->json([
            'message' => $mensaje,
        ], $status);
    }

    public function getTraslado($bodega)
    {
        $query = Bodegatransacion::select(['Bodegatransacions.*', 'm.BodegaDestino_id', 'm.BodegaOrigen_id', 'da.id as Detallearticulo_id',
            'da.Producto as Medicamento', 'da.CUM_completo', 'b.Nombre as Bodega_Nombre',
            'l.Numlote', 'l.Fvence'])
            ->join('movimientos as m', 'Bodegatransacions.Movimiento_id', 'm.id')
            ->join('bodegas as b', 'm.BodegaOrigen_id', 'b.id')
            ->join('bodegarticulos as ba', 'Bodegatransacions.Bodegarticulo_id', 'ba.id')
            ->join('lotes as l', 'Bodegatransacions.Lote_id', 'l.id')
            ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
            ->where('m.Estado_id', 1)
            ->where('Bodegatransacions.Estado_id', 4)
            ->where('m.Tipotransacion_id', 2);
            if(intval($bodega) > 0){
                 $query->where('m.Bodegadestino_id', $bodega);
            }
            $articulos = $query->orderByDesc('Bodegatransacions.created_at')->get();
        return response()->json($articulos, 200);
    }

    public function acceptTransfer(Request $request)
    {
        $bodegarticulo = bodegarticulo::select('bodegarticulos.*')
            ->join('detallearticulos as da', 'bodegarticulos.Detallearticulo_id', 'da.id')
            ->where('bodegarticulos.Bodega_id', $request->BodegaDestino_id)
            ->where('da.id', $request->Detallearticulo_id)
            ->first();
        $lote = Lote::select('lotes.*')
            ->where('lotes.Bodegarticulo_id', $bodegarticulo->id)
            ->where('lotes.Numlote', $request->Numlote)
            ->first();
        if(isset($bodegarticulo) && intval($bodegarticulo->Stock < 0)){
            return response()->json([
                'message' => 'Saldo negativo en bodega',
            ], 401);
        }

        if(isset($lote) && intval($lote->Cantidadisponible < 0)){
            return response()->json([
                'message' => 'Saldo negativo en lote',
            ], 401);
        }

        if (!isset($bodegarticulo)) {
            $bodegarticulo = bodegarticulo::create([
                'Bodega_id'          => $request->BodegaDestino_id,
                'Detallearticulo_id' => $request->Detallearticulo_id,
                'Stock'              => $request->CantidadtotalFactura,
                'Cantidadmaxima'     => 0,
                'Cantidadminima'     => 0,
            ]);
        } else {
            $bodegarticulo->update([
                'Stock' => $bodegarticulo->Stock + $request->CantidadtotalFactura,
            ]);
        }

        if (!isset($lote)) {
            $lote = Lote::create([
                'Numlote'           => $request->Numlote,
                'Fvence'            => $request->Fvence,
                'Cantidadisponible' => $request->CantidadtotalFactura,
                'Bodegarticulo_id'  => $bodegarticulo->id,
                'Estado_id'         => 1,
            ]);
        }

        $lote->update([
            'Cantidadisponible' => $lote->Cantidadisponible + $request->CantidadtotalFactura,
            'Fvence'            => $request->Fvence,
        ]);

        $bodegatransacion = Bodegatransacion::find($request->id);

        $bodegatransacion->update([
            'Estado_id' => 7,
        ]);

        return response()->json([
            'message' => '¡Traslado aceptado de manera exitosa!',
        ], 200);
    }

    public function updateTransfer(Bodegatransacion $bodegatransacion, Request $request)
    {

        $msg = 'Se modifica la cantidad de ' . $bodegatransacion->CantidadtotalFactura . ' a ' . $request->CantidadtotalFactura;

        Auditoria::create([
            'Descripcion'        => $msg,
            'Tabla'              => 'Bodegatransacions',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $bodegatransacion->id,
            'Motivo'             => $request->observaciones,
        ]);

        $bodegatransacion->update([
            'CantidadtotalFactura' => $request->CantidadtotalFactura,
        ]);

        return response()->json([
            'message' => 'Cantidad modificada de forma exitosa!',
        ], 200);
    }

    public function articulos(Movimiento $movimiento)
    {
        $articulos = Bodegatransacion::select(['Detallearticulos.CUM_completo', 'Detallearticulos.Principio_Activo',
            'Detallearticulos.Unidad_Medida', 'Detallearticulos.Nombresumi',
            'Detallearticulos.Cantidad', 'Detallearticulos.Forma_Farmaceutica',
            'Bodegatransacions.Cantidadtotal', 'Bodegatransacions.CantidadtotalFactura',
            'Bodegatransacions.Precio', 'Bodegatransacions.id', 'Bodegatransacions.Bodegarticulo_id',
            'Lotes.Numlote'])
            ->join('Bodegarticulos', 'Bodegatransacions.Bodegarticulo_id', 'Bodegarticulos.id')
            ->leftJoin('Lotes', 'Bodegatransacions.Lote_id', 'Lotes.id')
            ->join('Detallearticulos', 'Bodegarticulos.Detallearticulo_id', 'Detallearticulos.id')
            ->where('Bodegatransacions.Movimiento_id', $movimiento->id)
            ->get();
        return response()->json($articulos, 200);
    }

    public function update(Request $request, Movimiento $movimiento)
    {
        $movimiento->update($request->all());
        return response()->json([
            'message' => 'Movimiento actualizado con exito!',
        ]);
    }

    public function available(Request $request, Movimiento $movimiento)
    {
        $estado_available = 7;

        $bodegatransacion_a = Bodegatransacion::select('Cantidadtotal', 'Bodegarticulo_id', 'id')
            ->where('Movimiento_id', $movimiento->id)
            ->get();

        foreach ($bodegatransacion_a as $bodegatransacion_c) {

            $bodegatransacion_c->update([
                'Estado_id' => $estado_available,
            ]);
        }

        $movimiento->update([
            'Estado_id' => $estado_available,
        ]);

        return response()->json([
            'message' => 'Movimiento Actualizado con Exito!',
        ], 200);
    }

    public function entrace(Request $request, Movimiento $movimiento)
    {
        $estado_available = 3;

        $bodegatransacion_a = Bodegatransacion::select('Cantidadtotal', 'Bodegarticulo_id', 'id')
            ->where('Movimiento_id', $movimiento->id)
            ->get();

        foreach ($bodegatransacion_a as $bodegatransacion_c) {

            $bodegatransacion_c->update([
                'Estado_id'            => $estado_available,
                'CantidadtotalFactura' => 0,
            ]);
        }

        foreach ($request->bodega_transaccions as $bodegatransacion_r) {

            $bodegarticulo = bodegarticulo::where('id', $bodegatransacion_r['Bodegarticulo_id'])
                ->first();

            $bodegarticulo->Stock = $bodegarticulo->Stock + $bodegatransacion_r['CantidadtotalFactura'];
            $bodegarticulo->save();

            $loteencontrado = Lote::where('Numlote', $bodegatransacion_r['Numlote'])
                ->where('Bodegarticulo_id', $bodegatransacion_r['Bodegarticulo_id'])
                ->first();
            if ($loteencontrado == null) {
                $loteencontrado = Lote::create([
                    'Numlote'           => $bodegatransacion_r['Numlote'],
                    'Fvence'            => '2019-01-01',
                    'Cantidadisponible' => $bodegatransacion_r['CantidadtotalFactura'],
                    'Bodegarticulo_id'  => $bodegatransacion_r['Bodegarticulo_id'],
                ]);
            } else {
                $loteencontrado->update('Cantidadisponible', intval($loteencontrado->Cantidadisponible) + intval($bodegarticulo['Cantidadtotal']));
            }

            $bodegatransacion_c->update([
                'Estado_id'            => $estado_available,
                'Lote_id'              => $loteencontrado->id,
                'CantidadtotalFactura' => $bodegatransacion_r['CantidadtotalFactura'],
            ]);
        }

        $movimiento->update([
            'Estado_id' => $estado_available,
        ]);

        return response()->json([
            'message' => 'Movimiento Actualizado con Exito!',
        ], 200);
    }

    public function cancelar(Request $request, Movimiento $movimiento)
    {

        $notatransacion = Notastransacion::create([
            'usuario_id'    => auth()->user()->id,
            'Movimiento_id' => $movimiento->id,
            'Descripcion'   => $request->Descripcion,
        ]);

        $estado_cancelado = 6;

        $bodegatransacion_a = Bodegatransacion::select('Cantidadtotal', 'Lote_id', 'id')
            ->where('Movimiento_id', $movimiento->id)
            ->get();

        foreach ($bodegatransacion_a as $bodegatransacion_c) {

            $bodegatransacion_c->update([
                'Estado_id' => $estado_cancelado,
            ]);

            $movimiento->update([
                'Estado_id' => $estado_cancelado,
            ]);
        }

        return response()->json([
            'message' => 'Movimiento cancelado con Exito!',
        ], 200);

    }

    public function massInvoices(Request $request)
    {

        // $lines = (new FastExcel)->import($request->excel);
        $contents  = [];
        $rows      = [];
        $items     = [];
        $lines     = [];
        $c         = 0;
        $i         = 0;
        $file      = $request->file('excel');
        $open_file = fopen($file, 'r');
        $content   = fread($open_file, filesize($request->file('excel')));
        $rows      = preg_split('/\r\n/', $content);
        $headers   = explode(',', $rows[0]);

        // $this->checkFileNameFields($lines[0]);

        $i = -1;
        foreach ($rows as $row) {
            $i++;
            if ($i == 0) {
                continue;
            }

            $items[$i] = explode(',', $row);

        }
        $i = 0;
        foreach ($items as $item) {
            if ($i == 19) {
                $lines[$i][$headers[0]] = $item[0];
            }
            $i++;
        }

        return $lines;

        if (count($this->_inconsistencias) == 0) {
            foreach ($lines as $line) {
                $this->_index++;
                $this->checkFileFields($line);
            }
        }
        $this->_index = 1;
        if (count($this->_inconsistencias) == 0) {
            foreach ($lines as $line) {
                $this->_index++;
                $this->checkDBFileFields($line);
            }
        }

        return response()->json([
            'message' => $this->_inconsistencias,
        ], 200);
    }

    private function checkFileNameFields($fields)
    {

        if (!isset($fields['nombre articulo'])) {$this->pushInconsistencia("el campo 'nombre articulo' no existe", 1);}

        if (!isset($fields['CODIGO SUMI'])) {$this->pushInconsistencia("el campo 'CODIGO SUMI' no existe", 1);}

        if (!isset($fields['fecha de compra'])) {$this->pushInconsistencia("el campo 'fecha de compra' no existe", 1);}

        if (!isset($fields['#factura'])) {$this->pushInconsistencia("el campo '#factura' no existe", 1);}

        if (!isset($fields['Bodega'])) {$this->pushInconsistencia("el campo 'Bodega' no existe", 1);}

        if (!isset($fields['cantidad ordenada'])) {$this->pushInconsistencia("el campo 'cantidad ordenada' no existe", 1);}

        if (!isset($fields['cantidad recibida'])) {$this->pushInconsistencia("el campo 'cantidad recibida' no existe", 1);}

        if (!isset($fields['vr unitario'])) {$this->pushInconsistencia("el campo 'vr unitario' no existe", 1);}

        if (!isset($fields['total'])) {$this->pushInconsistencia("el campo 'total' no existe", 1);}

        if (!isset($fields['invima'])) {$this->pushInconsistencia("el campo 'invima' no existe", 1);}

        if (!isset($fields['lote'])) {$this->pushInconsistencia("el campo 'lote' no existe", 1);}

        if (!isset($fields['fecha de vencimiento'])) {$this->pushInconsistencia("el campo 'fecha de vencimiento' no existe", 1);}

        if (!isset($fields['CUM'])) {$this->pushInconsistencia("el campo 'CUM' no existe", 1);}

    }

    private function checkFileFields($fields)
    {

        if (trim($fields['nombre articulo']) == '') {$this->pushInconsistencia("el campo 'nombre articulo' está vacio");}

        if (trim($fields['CODIGO SUMI']) == '') {$this->pushInconsistencia("el campo 'CODIGO SUMI' está vacio");}

        if (trim($fields['fecha de compra']) == '') {$this->pushInconsistencia("el campo 'fecha de compra' está vacio");}

        if (trim($fields['#factura']) == '') {$this->pushInconsistencia("el campo '#factura' está vacio");}

        if (trim($fields['Bodega']) == '') {$this->pushInconsistencia("el campo 'Bodega' está vacio");}

        if (trim($fields['cantidad ordenada']) == '') {$this->pushInconsistencia("el campo 'cantidada ordenada' está vacio");}

        if (trim($fields['cantidad recibida']) == '') {$this->pushInconsistencia("el campo 'cantidad recibida' está vacio");}

        if (trim($fields['vr unitario']) == '') {$this->pushInconsistencia("el campo 'vr unitario' está vacio");}

        if (trim($fields['total']) == '') {$this->pushInconsistencia("el campo 'total' está vacio");}

        if (trim($fields['invima']) == '') {$this->pushInconsistencia("el campo 'invima' está vacio");}

        if (trim($fields['lote']) == '') {$this->pushInconsistencia("el campo 'lote' está vacio");}

        if (trim($fields['fecha de vencimiento']) == '') {$this->pushInconsistencia("el campo 'fecha de vencimiento' está vacio");}

        if (trim($fields['CUM']) == '') {$this->pushInconsistencia("el campo 'CUM' está vacio");}
    }

    private function checkDBFileFields($fields)
    {
        $endMsg = "' no está en base de datos";
        if (!$this->isDbCodesumi($fields['CODIGO SUMI'])) {$this->pushInconsistencia("el 'CODIGO SUMI' '" . $fields['CODIGO SUMI'] . $endMsg);}

        if (!$this->isDbBodega($fields['Bodega'])) {$this->pushInconsistencia("la 'Bodega' '" . $fields['Bodega'] . $endMsg);}

        if (!$this->isDbCUM($fields['CUM'])) {$this->pushInconsistencia("el 'CUM' '" . $fields['CUM'] . $endMsg);}
    }

    private function isDbCodesumi($codesumi)
    {
        $code = Codesumi::where('Codigo', $codesumi)->first();

        if (isset($code)) {return true;}
        return false;
    }

    private function isDbBodega($bodega)
    {
        $b = Bodega::where('Nombre', $bodega)->first();

        if (isset($b)) {return true;}
        return false;
    }

    private function isDbCUM($CUM)
    {
        $detalle = Detallearticulo::where('CUM_completo', $CUM)->first();

        if (isset($detalle)) {return true;}
        return false;
    }

    private function pushInconsistencia($msg, $type = 2)
    {
        if ($type == 1) {array_push($this->_inconsistencias, $msg);} else {array_push($this->_inconsistencias, "en la linea " . $this->_index . " " . $msg);}
    }

    public function entradaOrdenComprar(Request $request,$codigoFactura){
        $articulos = $request->all();
        $solicitudCompra = SolicitudCompra::find($articulos[0]["solicitudcompra_id"]);
        $movimiento = Movimiento::create([
            'Tipotransacion_id' => 4,
            'Numfactura'        => $codigoFactura,
            'Estado_id'         => 1,
            'usuario_id'        => auth()->user()->id,
            'BodegaOrigen_id'   => $solicitudCompra->bodega_id,
        ]);
        foreach ($articulos as $articulo){
            if(!$articulo["devolucion"]) {
                $finalizar = false;
                $cantidadRecibidad = isset($articulo["CantidadLote"])?intval($articulo["CantidadLote"]):0;
                $idDetalleArticulo = $articulo["detallearticulo_id"];
                if ($articulo["tipoNovedad"]) {
                    NovedadesOrdenCompra::create([
                        "tipo_novedad" => $articulo["tipoNovedad"],
                        "ordencompras_id" => $articulo["id"],
                        "cantidad" => $articulo["cantidadRecibida"],
                        "nuevo_precio" => $articulo["nuevoPrecio"],
                        "observacion" => $articulo["observacion"],
                        "devolver" => $articulo["devolucion"],
                        "lote" => $articulo["lote"],
                        "numero_factura" => $codigoFactura,
                        "fecha_vence" => $articulo["fecha_vencimiento"],
                        'nuevoDetallearticulo_id' => $articulo["nuevoMedicamento"]
                    ]);
                    if ($articulo["tipoNovedad"] === 'Sobrante' || $articulo["tipoNovedad"] === 'Faltante' || $articulo["tipoNovedad"] === 'Averias') {
                        $cantidadRecibidad = $articulo["cantidadRecibida"];
                        $finalizar = true;
                    }
                    if ($articulo["tipoNovedad"] === 'Producto No Solicitado') {
                        $idDetalleArticulo = $articulo["nuevoMedicamento"];
                    }
                    if ($articulo["tipoNovedad"] === 'Nuevo Precio' || $articulo["tipoNovedad"] === 'Producto No Solicitado') {
                        PrecioProveedorMedicamento::updateOrCreate([
                            'detallearticulo_id' => $idDetalleArticulo,
                            'sedeproveedore_id' => $solicitudCompra->sedeproveedore_id
                        ],
                            [
                                'precio_unidad' => abs($articulo["nuevoPrecio"])
                            ]
                        );
                        HistoricoPrecioProveedorMedicamento::create([
                            'detallearticulo_id' => $idDetalleArticulo,
                            'sedeproveedore_id' => $solicitudCompra->sedeproveedore_id,
                            'precio_unidad' => abs($articulo["nuevoPrecio"]),
                            'ordencompra_id' => $articulo["id"]
                        ]);
                    }
                }

                if (intval($cantidadRecibidad) > 0) {
                $BodegaArticulo = bodegarticulo::where('bodegarticulos.Bodega_id', $solicitudCompra->bodega_id)
                    ->where('bodegarticulos.Detallearticulo_id', $idDetalleArticulo)->first();
                if (!$BodegaArticulo) {
                    $BodegaArticulo = bodegarticulo::create(['Bodega_id' => $solicitudCompra->bodega_id,
                        'Detallearticulo_id' => $idDetalleArticulo,
                        'Stock' => 0,
                        'Cantidadmaxima' => 0,
                        'Cantidadminima' => 0
                    ]);
                }
                $lote = Lote::select('lotes.*')
                    ->where('lotes.Numlote', $articulo["lote"])
                    ->where('lotes.Bodegarticulo_id', $BodegaArticulo->id)
                    ->first();
                if (!$lote) {
                    bodegarticulo::where('id', $BodegaArticulo->id)
                        ->update(['Stock' => abs(intval($BodegaArticulo->Stock) + intval($cantidadRecibidad))]);
                    $nuevoLote = Lote::create([
                        'Numlote' => $articulo["lote"],
                        'Fvence' => $articulo["fecha_vencimiento"],
                        'Cantidadisponible' => intval($cantidadRecibidad),
                        'Bodegarticulo_id' => $BodegaArticulo->id,
                        'Estado_id' => 1
                    ]);
                    Bodegatransacion::create([
                        'Lote_id' => $nuevoLote->id,
                        'Movimiento_id' => $movimiento->id,
                        'Cantidadtotal' => $cantidadRecibidad,
                        'CantidadtotalFactura' => $cantidadRecibidad,
                        'Cantidadtotalinventario' => $cantidadRecibidad,
                        'Precio' => intval($articulo['precio_unidad']),
                        'Valor' => intval($articulo['precio_unidad']) * intval($cantidadRecibidad),
                        'Valortotal' => 0,
                        'Valorpromedio' => 0,
                        'Estado_id' => 1,
                        'Bodegarticulo_id' => $BodegaArticulo->id,
                    ]);
                    Ordencompra::where('id', $articulo["id"])
                        ->update(['Estado_id' => ((intval($articulo["saldo_cantidad"]) - intval($cantidadRecibidad) === 0) || $finalizar === true ? 13 : 7), 'Bodegarticulo_id' => $BodegaArticulo->id, 'numfactura' => $codigoFactura, 'lote' => $articulo["lote"], 'fecha_vencimiento' => $articulo["fecha_vencimiento"], 'usuario_ingresa' => auth()->user()->id, 'saldo_cantidad' => intval($articulo["saldo_cantidad"]) - intval($cantidadRecibidad)]);
                } else {
                    $bodegaArticulo = bodegarticulo::find($lote["Bodegarticulo_id"]);
                    $stockAnterior = $bodegaArticulo->Stock;
                    $bodegaArticulo->update([
                        'Stock' => abs(intval($stockAnterior) + intval($cantidadRecibidad))
                    ]);
                    Lote::where('id', $lote["id"])->update(['Fvence' => $articulo["fecha_vencimiento"], 'Cantidadisponible' => abs(intval($lote["Cantidadisponible"]) + intval($cantidadRecibidad))]);

                    Bodegatransacion::create([
                        'Lote_id' => $lote["id"],
                        'Movimiento_id' => $movimiento->id,
                        'Cantidadtotal' => $cantidadRecibidad,
                        'CantidadtotalFactura' => $cantidadRecibidad,
                        'Cantidadtotalinventario' => abs(intval($lote["Cantidadisponible"]) + intval($cantidadRecibidad)),
                        'Precio' => intval($articulo['precio_unidad']),
                        'Valor' => intval($articulo['precio_unidad']) * intval($cantidadRecibidad),
                        'Valortotal' => 0,
                        'Valorpromedio' => 0,
                        'Estado_id' => 1,
                        'Bodegarticulo_id' => $bodegaArticulo->id,
                    ]);
                    Ordencompra::where('id', $articulo["id"])
                        ->update(['Estado_id' => ((intval($articulo["saldo_cantidad"]) - intval($cantidadRecibidad) === 0) || $finalizar === true ? 13 : 7), 'Bodegarticulo_id' => $bodegaArticulo->id, 'numfactura' => $codigoFactura, 'lote' => $articulo["lote"], 'fecha_vencimiento' => $articulo["fecha_vencimiento"], 'usuario_ingresa' => auth()->user()->id, 'saldo_cantidad' => intval($articulo["saldo_cantidad"]) - intval($cantidadRecibidad)]);
                }
            }
            }else{
                $novedadOrdenCompra = NovedadesOrdenCompra::create([
                    "tipo_novedad" => $articulo["tipoNovedad"],
                    "ordencompras_id" => $articulo["id"],
                    "cantidad" =>  $articulo["cantidadRecibida"],
                    "nuevo_precio" =>  $articulo["nuevoPrecio"],
                    "observacion" => $articulo["observacion"],
                    "devolver" =>  $articulo["devolucion"],
                    "lote" => $articulo["lote"],
                    "numero_factura" => $codigoFactura,
                    "fecha_vence" => $articulo["fecha_vencimiento"],
                    "nuevoDetallearticulo_id" => $articulo["nuevoMedicamento"]

                ]);
                Ordencompra::where('id',$articulo["id"])
                    ->update(['Estado_id' => 18,'numfactura' => $codigoFactura,'lote' => $articulo["lote"],'fecha_vencimiento' => $articulo["fecha_vencimiento"],'usuario_ingresa'=> auth()->user()->id]);
            }

        }
        $ordenesCompra = Ordencompra::where('solicitudcompra_id',$solicitudCompra->id)->where("Estado_id",7)->get();
        if(!count($ordenesCompra)){
            $solicitudCompra->Estado_id = 13;
            $solicitudCompra->save();
        }
        return response()->json(['message' => 'Entrada Exitosa']);
    }

    public function actaResolucion(Request $request,$solicitudCompra){
        $ordenCompra = Ordencompra::select(['b.Nombre as Bodega',
            'da.codigo as Codigo',
            'da.Producto as Nombre Generico',
            'ordencompras.Cantidad as Cant',
            'c.Forma_Farmaceutica as Presentacion/Forma Farmaceutica',
            'l.Numlote as Lote',
            'l.Fvence as Fecha de vencimiento',
            'c.Registro_Sanitario as Registro Sanitario',
            'c.Titular as Laboratorio',
        ])
            ->join('solicitud_compras as sc','sc.id','ordencompras.solicitudcompra_id')
            ->join('bodegas as b','b.id','sc.bodega_id')
            ->join('bodegarticulos as ba','ba.id','ordencompras.Bodegarticulo_id')
            ->join('lotes as l','l.Bodegarticulo_id','b.id')
            ->join('detallearticulos as da','da.id','ordencompras.detallearticulo_id')
            ->join('cums as c','c.Cum_Validacion','da.Cum_Validacion')
            ->where('ordencompras.solicitudcompra_id',$solicitudCompra)
            ->distinct()->get()->toArray();
        return (new FastExcel($ordenCompra))->download('file.xls');
    }

    public function getNovedades(Bodega $bodega){
        $novedades = NovedadesOrdenCompra::select(['sc.id',
            'novedades_orden_compras.numero_factura',
            'da.Producto',
            't.nombre',
            'novedades_orden_compras.lote',
            'novedades_orden_compras.fecha_vence',
            'novedades_orden_compras.tipo_novedad',
            'novedades_orden_compras.observacion',
            'novedades_orden_compras.Cantidad',
            'novedades_orden_compras.devolver',
            'novedades_orden_compras.created_at'
        ])
            ->join('ordencompras as o','o.id','novedades_orden_compras.ordencompras_id')
            ->join('detallearticulos as da','da.id','o.detallearticulo_id')
            ->join('solicitud_compras as sc','sc.id','o.solicitudcompra_id')
            ->join('titulares as t','t.id','da.titular_id')
            ->where('sc.bodega_id',$bodega->id)
            ->get();
        return response()->json($novedades);
    }

    public function detallesSolicitudCompra($solicitud){
        $articulos = HistoricoPrecioProveedorMedicamento::select(['c.Codigo','c.Nombre','t.nombre as Fabricante','da.unidad_compra','o.cantidad',
                'historico_precio_proveedor_medicamentos.precio_unidad as Valor Unidad',DB::raw('(historico_precio_proveedor_medicamentos.precio_unidad * o.cantidad) as Total')])
            ->join('ordencompras as o','o.id','historico_precio_proveedor_medicamentos.ordencompra_id')
            ->join('detallearticulos as da','da.id','o.detallearticulo_id')
            ->join('codesumis as c','c.id','da.Codesumi_id')
            ->join('titulares as t','t.id','da.titular_id')
            ->where('o.solicitudcompra_id',$solicitud)
            ->where('o.estado_id',7)
            ->get()->toArray();
        return (new FastExcel($articulos))->download('file.xls');
    }

    public function getAllpendingTraslates(Request $request){
        $query = Bodegatransacion ::select(['Bodegatransacions.*', 'm.BodegaDestino_id', 'm.BodegaOrigen_id', 'da.id as Detallearticulo_id',
            'da.Producto as Medicamento', 'da.CUM_completo', 'b.Nombre as Bodega_Nombre','b2.Nombre as Bodega_Nombre_destino',
            'l.Numlote', 'l.Fvence','c.Nombre as descripcion'])
            ->join('movimientos as m', 'Bodegatransacions.Movimiento_id', 'm.id')
            ->join('bodegas as b', 'm.BodegaOrigen_id', 'b.id')
            ->join('bodegas as b2', 'm.BodegaDestino_id', 'b2.id')
            ->join('bodegarticulos as ba', 'Bodegatransacions.Bodegarticulo_id', 'ba.id')
            ->join('lotes as l', 'Bodegatransacions.Lote_id', 'l.id')
            ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
            ->join('codesumis as c','c.id','da.Codesumi_id')
            ->where('m.Estado_id', 1)
            ->where('Bodegatransacions.Estado_id', 4)
            ->where('m.Tipotransacion_id', 2);
        if($request->bodega){
            $query->where('m.BodegaDestino_id',$request->bodega);
        }
        if($request->codesumi){
            $query->where('c.id',$request->codesumi);
        }
        if($request->fechaDesde){
            $query->where('Bodegatransacions.created_at','>=',$request->fechaDesde);
        }
        if($request->fechaHasta){
            $query->where('Bodegatransacions.created_at','<=',$request->fechaHasta);
        }
            $query->orderByDesc('Bodegatransacions.created_at');

        $articulos = $query->get();


        $codesumis = Bodegatransacion::select(['c.id','c.Nombre'])
            ->join('movimientos as m', 'Bodegatransacions.Movimiento_id', 'm.id')
            ->join('bodegas as b', 'm.BodegaOrigen_id', 'b.id')
            ->join('bodegas as b2', 'm.BodegaDestino_id', 'b2.id')
            ->join('bodegarticulos as ba', 'Bodegatransacions.Bodegarticulo_id', 'ba.id')
            ->join('lotes as l', 'Bodegatransacions.Lote_id', 'l.id')
            ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
            ->join('codesumis as c','c.id','da.Codesumi_id')
            ->where('m.Estado_id', 1)
            ->where('Bodegatransacions.Estado_id', 4)
            ->where('m.Tipotransacion_id', 2)
            ->distinct()
            ->get();

            $data = [
                'solicitudes' => $articulos,
                'codigosSumi' => $codesumis
            ];
        return response()->json($data);
    }

    public function cancelTransfer(Request $request){

        $bodegatransacion = Bodegatransacion::find($request->id);
        $lote = Lote::find($bodegatransacion->Lote_id);
        $bodegaArticulo = bodegarticulo::find($lote->Bodegarticulo_id);
        $lote->update(['Cantidadisponible' => intval($lote->Cantidadisponible) + intval($bodegatransacion->CantidadtotalFactura)]);
        $bodegaArticulo->update(['Stock' => intval($bodegaArticulo->Stock)+intval($bodegatransacion->CantidadtotalFactura)]);
        $bodegatransacion->update(['Estado_id' => 6]);

        return response()->json([
            'message' => '¡Traslado cancelado de manera exitosa!',
        ], 200);
    }

    public function anularOrdenCompra(Request $request){

        $ordenesCompra = Ordencompra::where('solicitudcompra_id', $request->solicitudcompra_id)->where("Estado_id",13)->first();

        if($ordenesCompra){
            return response()->json([
                'message' => 'Ya realizo un movimiento parcial, no puede anularla!',
            ], 401);
        }else{

            SolicitudCompra::where('id', $request->solicitudcompra_id)->update([
                'estado_id' => 26
            ]);

            return response()->json([
                'message' => 'Orden de compra anulada con exito!',
            ], 200);
        }

    }

}
