<?php

namespace App\Http\Controllers\Bodegas;

use App\Modelos\Detallearticulo;
use App\User;
use App\Modelos\Us;
use App\Modelos\Lote;
use App\Modelos\Orden;
use App\Modelos\Bodega;
use App\Modelos\Movimiento;
use App\Modelos\Ordencompra;
use Illuminate\Http\Request;
use App\Modelos\bodegarticulo;
use App\Modelos\SolicitudCompra;
use App\Modelos\Bodegatransacion;
use App\Modelos\Solicitudbodegas;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\DetalleSolicitudBodega;
use Illuminate\Support\Facades\Validator;

class BodegaController extends Controller
{
    public function all()
    {
        $bodega = Bodega::select(['Bodegas.*', 'Municipios.Nombre as NombreMunicipio', 'Tipobodegas.Nombre as TipobodegaNombre'])
            ->join('Municipios', 'Bodegas.Municipio_id', 'Municipios.id')
            ->join('Tipobodegas', 'Bodegas.Tipobodega_id', 'Tipobodegas.id')
            ->get();
        return response()->json($bodega, 200);
    }

    public function getBodegaByRole()
    {
        $rolesUser = auth()->user()->getRoleNames()->toArray();
        $access    = $this->getAccess($rolesUser);
        $bodegas   = [];

        if (!empty($access)) {
            $bodegas = Bodega::select(['Bodegas.*', 'Municipios.Nombre as NombreMunicipio', 'Tipobodegas.Nombre as TipobodegaNombre'])
                ->join('Municipios', 'Bodegas.Municipio_id', 'Municipios.id')
                ->join('Tipobodegas', 'Bodegas.Tipobodega_id', 'Tipobodegas.id')
                ->where(function ($query) use ($access) {
                    foreach ($access as $row) {
                        $query->orWhere('Bodegas.Nombre', 'LIKE', '%' . $row . '%');
                    }
                })
                ->get();
        }

        return response()->json($bodegas, 201);

    }

    private function getAccess($rolesUser)
    {
        $access = [
            'Bodega Central',
            'Bodega Centro',
            'Bodega Argentina',
            'Bodega Bello',
            'Bodega Envigado',
            'Bodega Itagui',
            'Bodega Rionegro',
            'Bodega Apartado',
            'Bodega Turbo',
            'Bodega Caucasia',
            'Bodega Puerto Berrio',
            'Bodega Copacabana',
            'Bodega Domiciliaria',
            'Bodega Estadio',
            'Bodega Apoyo',
            'Bodega VillaNueva',
            'Bodega Quimioterapia',
            'Bodega Quibdo',
            'Bodega de Consumo Servicios Especiales',
            'Bodega Bucaramanga',
            'Bodega Caldas',
        ];

        $array = [];

        foreach ($access as $row) {
            if (in_array($row, $rolesUser)) {
                $bodega = explode(' ', $row);
                array_push($array, $bodega[1]);
            }
        }

        return $array;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string|unique:bodegas',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input  = $request->all();
        $bodega = Bodega::create($input);
        return response()->json([
            'message' => 'bodega fue creado con exito!',
        ], 201);
    }

    public function update(Request $request, Bodega $bodega)
    {
        $bodega->update($request->all());
        return response()->json([
            'message' => 'bodega actualizado con exito!',
        ]);
    }

    public function available(Request $request, Bodega $bodega)
    {
        $bodega->update([
            'Estado' => $request->Estado,
        ]);
        return response()->json([
            'message' => 'bodega Actualizado con Exito!',
        ], 200);
    }

    public function historicoDispensado(Request $request, Bodega $bodega)
    {
        $objDispensado = Orden::select(['p.Num_Doc', 'u.name', 'u.apellido',DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),'ordens.id AS Orden_id'])
            ->join('movimientos as m','ordens.id','m.Orden_id')
            ->join('cita_paciente as cp','cp.id','ordens.Cita_id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->join('users as u', 'm.usuario_id', 'u.id')
            ->where('m.BodegaOrigen_id', $bodega->id)
            ->where('m.Tipotransacion_id', 3)
            ->whereBetween('m.created_at', [$request->startDate.' 00:00:00.000', $request->finishDate.' 23:59:59.999']);
            if($request->usuario){
                $objDispensado->where('m.usuario_id',$request->usuario);
            }


//        $objDispensado = Movimiento::select(['movimientos.id','p.Num_Doc', 'movimientos.created_at', 'movimientos.usuario_id', 'u.name', 'u.apellido',DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),'movimientos.Orden_id'])->join('users as u', 'movimientos.usuario_id', 'u.id')
//            ->join('ordens as o','o.id','movimientos.Orden_id')
//            ->join('cita_paciente as cp','cp.id','o.Cita_id')
//            ->join('pacientes as p','p.id','cp.Paciente_id')
//            ->with(['bodegatransacion' => function ($query) use ($bodega) {
//                $query->select(['bodegatransacions.id', 'bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
//                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
//                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
//                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
//                    ->where('ba.Bodega_id', $bodega->id);
//            },
//            ])
//            ->where('BodegaOrigen_id', $bodega->id)
//            ->where('movimientos.Tipotransacion_id', 3)
//            ->whereBetween('movimientos.created_at', [$request->startDate.' 00:00:00.000', $request->finishDate.' 23:59:59.999']);
//            if($request->usuario){
//                $objDispensado->where('movimientos.usuario_id',$request->usuario);
//            }

        return response()->json($objDispensado->distinct()->get(), 200);
    }

    public function historicoEntradaFactura(Request $request, Bodega $bodega)
    {

        $objEntradaFactura = Movimiento::join('users as u', 'movimientos.usuario_id', 'u.id')
            ->with(['bodegatransacion' => function ($query) use ($bodega) {
                $query->select(['bodegatransacions.id', 'bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
                    ->where('ba.Bodega_id', $bodega->id);
            },
            ])
            ->where('BodegaOrigen_id', $bodega->id)
            ->where('movimientos.Tipotransacion_id', 4)
            ->whereBetween('movimientos.created_at', [$request->dateStart, $request->dateEnd])
            ->orderBy('movimientos.id', 'DESC')
            ->paginate(15,
                ['movimientos.id', 'movimientos.Numfactura', 'movimientos.created_at', 'movimientos.usuario_id', 'u.name', 'u.apellido']);

        return response()->json(
            $objEntradaFactura, 200);

    }

    public function historicoTraslado(Request $request, Bodega $bodega)
    {

        $objEntradaFactura = Movimiento::join('users as u', 'movimientos.usuario_id', 'u.id')
            ->with(['bodegatransacion' => function ($query) use ($bodega) {
                $query->select(['bodegatransacions.id', 'bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
                    ->where('ba.Bodega_id', $bodega->id);
            },
            ])
            ->where('BodegaOrigen_id', $bodega->id)
            ->where('movimientos.Tipotransacion_id', 2)
            ->whereBetween('movimientos.created_at', [$request->dateStart, $request->dateEnd])
            ->orderBy('movimientos.id', 'DESC')
            ->paginate(15,
                ['movimientos.id', 'movimientos.BodegaOrigen_id', 'movimientos.created_at', 'movimientos.usuario_id', 'u.name', 'u.apellido']);

        return response()->json(
            $objEntradaFactura, 200);
    }

    public function exphistoricoDispensado(Request $request, Bodega $bodega)
    {
        $objDispensado = Movimiento::join('users as u', 'movimientos.usuario_id', 'u.id')
            ->with(['bodegatransacion' => function ($query) use ($bodega) {
                $query->select(['bodegatransacions.id', 'bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
                    ->where('ba.Bodega_id', $bodega->id);
            },
            ])
            ->where('BodegaOrigen_id', $bodega->id)
            ->where('movimientos.Tipotransacion_id', 3)
            ->whereBetween('movimientos.created_at', [$request->startDate, $request->finishDate])
            ->orderBy('movimientos.id', 'DESC')
            ->paginate(15,
                ['movimientos.id', 'movimientos.Orden_id', 'movimientos.created_at', 'movimientos.usuario_id', 'u.name', 'u.apellido']);

        return response()->json($objDispensado, 200);
    }

    public function exphistoricoEntradaFactura(Request $request, Bodega $bodega)
    {

        $objEntradaFactura = Movimiento::join('users as u', 'movimientos.usuario_id', 'u.id')
            ->with(['bodegatransacion' => function ($query) use ($bodega) {
                $query->select(['bodegatransacions.id', 'bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
                    ->where('ba.Bodega_id', $bodega->id);
            },
            ])
            ->where('BodegaOrigen_id', $bodega->id)
            ->where('movimientos.Tipotransacion_id', 4)
            ->whereBetween('movimientos.created_at', [$request->dateStart, $request->dateEnd])
            ->orderBy('movimientos.id', 'DESC')
            ->paginate(15,
                ['movimientos.id', 'movimientos.Numfactura', 'movimientos.created_at', 'movimientos.usuario_id', 'u.name', 'u.apellido']);

        return response()->json(
            $objEntradaFactura, 200);

    }

    public function exphistoricoTraslado(Request $request, Bodega $bodega)
    {

        $objEntradaFactura = Movimiento::join('users as u', 'movimientos.usuario_id', 'u.id')
            ->with(['bodegatransacion' => function ($query) use ($bodega) {
                $query->select(['bodegatransacions.id', 'bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id');
            },
            ])
            ->where('BodegaOrigen_id', $bodega->id)
            ->where('movimientos.Tipotransacion_id', 2)
            ->whereBetween('movimientos.created_at', [$request->dateStart, $request->dateEnd])
            ->orderBy('movimientos.id', 'DESC')
            ->paginate(15,
                ['movimientos.id', 'movimientos.BodegaOrigen_id', 'movimientos.created_at', 'movimientos.usuario_id', 'u.name', 'u.apellido']);

        return response()->json(
            $objEntradaFactura, 200);
    }
    public function movimientos(Request $request, Bodega $bodega)
    {
        return [$request->all(), $bodega];
    }

    public function pendientesBodega(Request $request, $bodega)
    {
        $ordenes = [];
        $arBodega = Bodega::find($bodega);
        if($arBodega){
            $ordenes = Orden::select(['p.Tipo_Doc',
                'p.Num_Doc',
                'p.Primer_Nom',
                'p.SegundoNom',
                'p.Primer_Ape',
                'p.Segundo_Ape',
                'e.nombre as Entidad',
                'p.Telefono',
                'p.Celular1',
                'p.Celular2',
                'p.Correo1',
                'p.Correo2',
                'ordens.id',
                'cs.Nombre',
                'dao.Cantidadmensualdisponible',
                'ordens.created_at',
                'ordens.fecha_pendiente'])
                ->distinct()
                ->join('movimientos as m', 'm.Orden_id', 'ordens.id')
                ->join('bodegatransacions as bt', 'bt.Movimiento_id', 'm.id')
                ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
                ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                ->join('detaarticulordens as dao', 'dao.Orden_id', 'ordens.id')
                ->join('codesumis as cs', 'dao.codesumi_id', 'cs.id')
                ->join('entidades as e','p.entidad_id','e.id')
                ->whereIn('dao.Estado_id', [18,19])
                ->where('m.BodegaOrigen_id', $arBodega->id)
                ->whereBetween('ordens.fecha_pendiente', [$request->startDate, $request->finishDate . ' 23:59:59.000'])
                ->orderBy('ordens.created_at', 'asc')
                ->get();
        }else{
            $ordenes = Orden::select(['p.Tipo_Doc',
                'p.Num_Doc',
                'p.Primer_Nom',
                'p.SegundoNom',
                'p.Primer_Ape',
                'p.Segundo_Ape',
                'e.nombre as Entidad',
                'p.Telefono',
                'p.Celular1',
                'p.Celular2',
                'p.Correo1',
                'p.Correo2',
                'ordens.id',
                'cs.Nombre',
                'dao.Cantidadmensualdisponible',
                'ordens.created_at',
                'ordens.fecha_pendiente'])
                ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
                ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                ->join('detaarticulordens as dao', 'dao.Orden_id', 'ordens.id')
                ->join('codesumis as cs', 'dao.codesumi_id', 'cs.id')
                ->join('entidades as e','p.entidad_id','e.id')
                ->whereIn('ordens.Estado_id', [18, 19])
                ->whereIn('dao.Estado_id', [18, 19, 1, 7])
                ->whereBetween('ordens.fecha_pendiente', [$request->startDate, $request->finishDate . ' 23:59:59.000'])
                ->orderBy('ordens.created_at', 'asc')
                ->distinct()
                ->get();
        }
        return response()->json($ordenes, 200);
    }

    public function saldoBodega(Request $request, Bodega $bodega)
    {

//        $exportarsaldos = Collect(DB::select("exec dbo.ExportarSaldos" . " $bodega->id"));
        $exportarsaldos = bodegarticulo::select([
            'b.codigo',
            'c.Nombre',
            'b.Descripcion_Comercial',
            't.nombre as titular',
            'd.Numlote',
            'd.Fvence',
            'd.Cantidadisponible',
            DB::raw('(select TOP 1 precio_unidad from precio_proveedor_medicamentos where precio_proveedor_medicamentos.detallearticulo_id = b.id) as precio_unidad') ,
        ])
            ->join('detallearticulos as b', 'bodegarticulos.Detallearticulo_id' , 'b.id')
            ->join('codesumis as c', 'b.Codesumi_id' , 'c.id')
            ->join('lotes as d', 'bodegarticulos.id' , 'd.Bodegarticulo_id')
            ->leftjoin('titulares as t','t.id','b.titular_id')
            ->where('bodegarticulos.Bodega_id',$bodega->id)
            ->where('d.Cantidadisponible','>',0)
            ->get();
        $array = json_decode($exportarsaldos, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function solicitudAjusteExistencia(Request $request, Bodega $bodega)
    {
        $solicitud = Solicitudbodegas::create([
            'Bodegaorigen_id'    => $bodega->id,
            'Usuariosolicita_id' => auth()->user()->id,
            'Motivo'             => $request->motivo,
            'Tipotransacion_id'  => $request->Tipotransacion_id,
        ]);

        foreach ($request->ajustes as $articulo) {
            $detalle = new DetalleSolicitudBodega;
                $detalle->Bodegarticulo_id = ($articulo['bodegaArticulo'] ?? null);
                $detalle->Lote             = $articulo['lote'];
                $detalle->Cantidad         = $articulo['Cantidadtotal'];
                $detalle->detallearticulo_id           = $articulo['id'];
                $detalle->Fvence           = $articulo['Fvence'];
            $solicitud->detalles()->save($detalle);
        }
        return response()->json([
            'solicitud' => $solicitud->id,
            'message'   => 'Solicitud enviada exitosamente',
        ], 200);
    }

    public function getSolicitudAjusteExistencia(Request $request, Bodega $bodega)
    {

        $solicitudes = Solicitudbodegas::join('detalle_solicitud_bodega as dsb', 'dsb.SolicitudBodega_id', 'solicitudbodegas.id')
            ->leftjoin('detallearticulos as da', 'dsb.detallearticulo_id', 'da.id')
            ->leftjoin('bodegarticulos as ba', 'dsb.Bodegarticulo_id', 'ba.id')
            ->where('solicitudbodegas.Bodegaorigen_id', $bodega->id)
            ->where('solicitudbodegas.Tipotransacion_id', $request->Tipotransacion_id)
            ->where('dsb.Estado_id', 1)
            ->get(['dsb.id as detalle_id', 'solicitudbodegas.id', 'solicitudbodegas.Motivo', 'da.Producto', 'dsb.Lote', 'dsb.Fvence', 'dsb.Cantidad', 'dsb.Bodegarticulo_id','dsb.detallearticulo_id']);

        return response()->json($solicitudes, 200);
    }

    public function createAjusteExistencia(Request $request, Bodega $bodega)
    {
        // return [$request->all(),$bodega];

        $movimiento = Movimiento::create([
            'Tipotransacion_id' => $request->tipotransacion_id,
            'BodegaOrigen_id'   => $bodega->id,
            'Estado_id'         => 1,
            'usuario_id'        => auth()->user()->id,
        ]);

        foreach ($request->articulos as $articulo) {
            if(isset($articulo['Bodegarticulo_id'])){
                $lote = Lote::where('Bodegarticulo_id', $articulo['Bodegarticulo_id'])->where('Numlote', $articulo['Lote'])->first();
            }else{
                $bodegarticulo = bodegarticulo::where('Detallearticulo_id',$articulo['detallearticulo_id'])->where('Bodega_id',$bodega->id)->first();
                $articulo['Bodegarticulo_id'] = $bodegarticulo->id;
                $lote = Lote::create(['Numlote'=> $articulo['Lote'],'Cantidadisponible'=>0,'Bodegarticulo_id'=>$bodegarticulo->id,'Estado_id'=>1,'Fvence'=>$articulo["Fvence"]]);
            }
            if ($request->tipotransacion_id == 6) {
                $lote->Cantidadisponible = $lote->Cantidadisponible + $articulo['Cantidad'];

            } else {
                if ($lote->Cantidadisponible > $articulo['Cantidad']) {
                    $lote->Cantidadisponible = $lote->Cantidadisponible - $articulo['Cantidad'];
                } else {
                    $lote->Cantidadisponible = $articulo['Cantidad'] - $lote->Cantidadisponible;
                }
            }
            $lote->save();
            $bodegarticulo = bodegarticulo::find($articulo['Bodegarticulo_id']);

            $sumalotes = Lote::selectRaw('SUM(Cantidadisponible) as sum')
                ->where('Bodegarticulo_id', $bodegarticulo->id)->first();
            $bodegarticulo->Stock = $sumalotes->sum;
            $bodegarticulo->save();
            $transaccion                          = new Bodegatransacion;
            $transaccion->Lote_id                 = $lote->id;
            $transaccion->Cantidadtotal           = $articulo['Cantidad'];
            $transaccion->CantidadtotalFactura    = $articulo['Cantidad'];
            $transaccion->Cantidadtotalinventario = $bodegarticulo->Stock;
            $transaccion->Precio                  = 0;
            $transaccion->Valor                   = 0;
            $transaccion->Valortotal              = 0;
            $transaccion->Valorpromedio           = 0;
            $transaccion->Motivo                  = $articulo['Motivo'];
            $transaccion->Bodegarticulo_id        = $bodegarticulo->id;
            $transaccion->Estado_id               = 1;

            $movimiento->bodegatransacion()->save($transaccion);

            DetalleSolicitudBodega::find($articulo['detalle_id'])->update(['Estado_id' => 7, 'Usuarioresponde_id' => auth()->user()->id]);

        }

        return response()->json([
            'message' => 'Solicitud confirmada con exito',
        ], 200);
    }

    public function usuariosDispensa($bodega){
        $usuarios = User::select([DB::raw("CONCAT(users.name,' ',users.apellido) as name"),'users.id'])->whereIn("id",function ($query) use ($bodega) {
            $query->select('usuario_id')
                ->from('movimientos')
                ->where('Tipotransacion_id',3)
                ->where('BodegaOrigen_id',$bodega)
                ->distinct();
        })->get();
        return response()->json($usuarios);
    }

    public function historicoDispensadoDetalle(Request $request,$bodega){
        $detalle = Bodegatransacion::select(['bodegatransacions.id','bodegatransacions.created_at','bodegatransacions.Cantidadtotal', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.Movimiento_id', 'lo.Numlote', 'da.Cum_Validacion', 'da.Producto'])
                    ->join('lotes as lo', 'bodegatransacions.Lote_id', 'lo.id')
                    ->join('bodegarticulos as ba', 'lo.Bodegarticulo_id', 'ba.id')
                    ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
                    ->join('movimientos as m','m.id','bodegatransacions.Movimiento_id')
                    ->where('ba.Bodega_id', $bodega)
                    ->where('m.Orden_id',$request->orden)
                    ->whereBetween('m.created_at', [$request->startDate.' 00:00:00.000', $request->finishDate.' 23:59:59.999']);
            if($request->usuario){
                $detalle->where('m.usuario_id',$request->usuario);
            }
        ;
        return response()->json($detalle->get());
    }

    public function solicitudCompraFacturas(Request $request, $bodega){

        $facturas = SolicitudCompra::select(['orden.numfactura', 'sp.Nombre as proveedor', 'sp.id as proveedor_id',
        DB::raw("(select TOP 1 created_at from ordencompras where numfactura = orden.numfactura ) as created_at")])
        ->join('ordencompras as orden','solicitud_compras.id','orden.solicitudcompra_id')
        ->join('sedeproveedores as sp','sp.id','solicitud_compras.sedeproveedore_id')
        ->where('solicitud_compras.bodega_id', $bodega)
        ->where('orden.Estado_id',13)
        ->whereNotNull('orden.numfactura')
        ->whereBetween('orden.created_at', [$request->dateStart.' 00:00:00.000', $request->dateEnd.' 23:59:59.999'])
        ->groupBy('orden.numfactura','sp.Nombre', 'sp.id')
        ->get();

        return response()->json($facturas);

    }

    public function minMax($bodega){

        $dispensados = Detallearticulo::select([
            'm.CodigoSumi as Codigo',
            'm.Producto',
            'm.6 as v6',
            'm.5 as v5',
            'm.4 as v4',
            'm.3 as v3',
            'm.2 as v2',
            'm.1 as v1',
            DB::raw('SUM(ba.Stock) AS actual'),
            'detallearticulos.critico',
            'detallearticulos.abc'
        ])
            ->join('codesumis as cs','cs.id','detallearticulos.Codesumi_id')
            ->join('Min_Max as m','m.CodigoSumi','cs.Codigo')
            ->join('bodegarticulos as ba','ba.Detallearticulo_id','detallearticulos.id')
            ->where('m.Bodega_id',$bodega)
            ->where('ba.Bodega_id',$bodega)
            ->groupBy('m.CodigoSumi','m.Producto','m.Bodega_id','m.Bodega_Nombre','m.6','m.5','m.4','m.3','m.2','m.1','detallearticulos.critico','detallearticulos.abc')
            ->get()->toArray();

        $totalDispensados = array_reduce($dispensados, function($suma, $item)
        {
            return $suma += ($item["v6"]+$item["v5"]+$item["v4"]+$item["v3"]+$item["v2"]+$item["v1"]);
        });


        return response()->json(["dispensados"=>$dispensados,'total_dispensados'=>intval($totalDispensados)]);
    }
}
