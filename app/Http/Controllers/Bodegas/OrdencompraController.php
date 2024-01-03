<?php

namespace App\Http\Controllers\Bodegas;

use App\Modelos\Detallearticulo;
use App\Modelos\HistoricoPrecioProveedorMedicamento;
use App\Modelos\PrecioProveedorMedicamento;
use App\User;
use App\Modelos\Bodega;
use App\Modelos\Ordencompra;
use Illuminate\Http\Request;
use App\Modelos\SolicitudCompra;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrdencompraController extends Controller
{

    public function allOrdens(Bodega $bodega)
    {
        $solicitudes = Ordencompra::select(['ordencompras.id', 'cs.Nombre', 'da.CUM_completo', 'da.Titular', 'ordencompras.Cantidad'])
            ->join('bodegarticulos as ba', 'ordencompras.Bodegarticulo_id', 'ba.id')
            ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
            ->join('codesumis as cs', 'da.Codesumi_id', 'cs.id')
            ->where('ba.Bodega_id', $bodega->id)
            ->where('ordencompras.Estado_id', 15)
            ->get();

        return response()->json($solicitudes, 200);
    }

    public function allacepted(Bodega $bodega)
    {
        $solicitudes = Ordencompra::select(['ordencompras.id', 'ba.id as bodegaarticulo_id', 'cs.Nombre', 'da.CUM_completo', 'da.Titular', 'ordencompras.Cantidad','ordencompras.created_at'])
            ->join('bodegarticulos as ba', 'ordencompras.Bodegarticulo_id', 'ba.id')
            ->join('detallearticulos as da', 'ba.Detallearticulo_id', 'da.id')
            ->join('codesumis as cs', 'da.Codesumi_id', 'cs.id')
            ->where('ba.Bodega_id', $bodega->id)
            ->where('ordencompras.Estado_id', 7)
            ->orderBy('ordencompras.created_at','desc')
            ->get();

        return response()->json($solicitudes, 200);
    }

    public function store(Request $request, Bodega $bodega)
    {

        $solicitudCompra = SolicitudCompra::create([
            'bodega_id' => $bodega->id,
            'codigo' => $request->codigo,
            'estado_id'  => 15,
            'sedeproveedore_id' => $request->sedeselected
        ]);
        foreach ($request->bodegarticulos as $bodegarticulo) {
            Ordencompra::create([
                'Cantidad'         => $bodegarticulo['Cantidadtotal'],
                'detallearticulo_id' => $bodegarticulo['bodegaArticulo']['id'],
                'solicitudcompra_id' => $solicitudCompra->id,
                'Usuario_id'       => auth()->user()->id,
                'Estado_id'        => 15,
                'cantidad_inicial' => $bodegarticulo['Cantidadtotal'],
                'saldo_cantidad'   => $bodegarticulo['Cantidadtotal']
            ]);
        }

        return response()->json([
            'message' => 'Solucitud de compra creada con exito!',
            'solicitud' => $solicitudCompra->id
        ], 201);
    }

    public function update(Request $request, Ordencompra $ordencompra)
    {
        $ordencompra->update([
            'Cantidad' => $request->Cantidad,
        ]);
        return response()->json([
            'message' => 'Cantidad actualizada con exito!',
        ]);
    }

    public function acceptRequest(Request $request, Bodega $bodega)
    {
        $Prestador_id = $request->sedeselected;
        foreach ($request->articuloSelected as $request) {
            $orden = Ordencompra::find($request['id']);

            $orden->update([
                'Cantidad'           => $request['Cantidad'],
                'Estado_id'          => 7,
                'Usuarioresponde_id' => auth()->user()->id,
                'Prestador_id'       => $Prestador_id,
            ]);
        }

        return response()->json([
            'message' => 'Solicitud de compras aceptadas!',
        ], 200);
    }

    public function deshabilitarOrden(Request $request, Ordencompra $ordencompra)
    {
        $ordencompra->update([
            'Estado_id' => 6,
        ]);
        return response()->json([
            'message' => 'Orden de compra deshabilitada con exito!',
        ]);
    }

    public function getSolicitudesPendientes(Bodega $bodega){
        $solicitudes = SolicitudCompra::select(['solicitud_compras.*',DB::raw("(select TOP 1 CONCAT(u.name,' ',u.apellido) as Usuario_crea from ordencompras  join users as u ON u.id=ordencompras.Usuario_id where ordencompras.solicitudcompra_id =solicitud_compras.id) as usuario")])
            ->where('solicitud_compras.estado_id',15)
            ->where('solicitud_compras.bodega_id',$bodega->id)->get();
        return response()->json($solicitudes, 200);
    }

    public  function getSolicitudesPendientesDetalles($solicitud,$proveedor){

        $detalle = Ordencompra::select(['ordencompras.id','ordencompras.solicitudcompra_id','c.Nombre as medicamento','t.nombre as titular',DB::raw("CONCAT(u.name,' ',u.apellido) as Usuario_crea"),'ordencompras.Cantidad',DB::raw("1 as state"),'da.unidad_compra','ppm.precio_unidad','ppm.precio_unidad as precio_inicial','ordencompras.cantidad_inicial'])
            ->join('detallearticulos as da','da.id','ordencompras.detallearticulo_id')
            ->join('codesumis as c','c.id','da.Codesumi_id')
            ->join('titulares as t','t.id','da.titular_id')
            ->join('users as u','u.id','ordencompras.Usuario_id')
            ->leftjoin('precio_proveedor_medicamentos as ppm',function ($join) use ($proveedor){
                $join->on('ppm.detallearticulo_id', '=', 'da.id');
                $join->on('ppm.sedeproveedore_id','=',DB::raw($proveedor));
            })
            ->where('ordencompras.solicitudcompra_id',$solicitud)
            ->get();

        return response()->json($detalle, 200);
    }
    public function generarAutorizacion($estado,$proveedor,Request $request){

        $type = 'success';
        $message = ($estado == 7?'Orden de compra autorizada con exito!':'Orden de compra anulada con exito!');

        if($estado != 7){
            SolicitudCompra::where('id',$request[0]["solicitudcompra_id"])->update(['estado_id'=>$estado,'sedeproveedore_id' => $proveedor]);
        }

        if($estado == 7){

            $flag = 0;

            foreach ($request->all() as $ordenes){
               if($ordenes["Cantidad"] <= 0 && $ordenes["state"] == 1){
                   $flag = 1;
               }
                if($ordenes["precio_unidad"] <= 0 && $ordenes["state"] == 1){
                    $flag = 2;
                }
            }

            if($flag === 0){
                foreach ($request->all() as $ordenes){
                    $ordenCompra = Ordencompra::find($ordenes["id"]);
                    if($estado == 7 && $ordenes["state"] == 1){
                        $ordenCompra->update(['Cantidad'=> abs($ordenes["Cantidad"]),'Estado_id'=> ($ordenes["state"] == 1 || $ordenes["state"] == 3)?7:26,'Usuarioresponde_id' => auth()->user()->id]);
                        PrecioProveedorMedicamento::updateOrCreate([
                            'detallearticulo_id' => $ordenCompra->detallearticulo_id,
                            'sedeproveedore_id' =>$proveedor
                        ],
                            [
                                'precio_unidad' => abs($ordenes["precio_unidad"])
                            ]
                        );
                    }else{
                        $ordenCompra->update(['Estado_id'=> 26,'Usuarioresponde_id' => auth()->user()->id]);
                    }
    
                    if($ordenes["state"] == 1 && $estado == 7){
                        HistoricoPrecioProveedorMedicamento::create([
                            "precio_unidad" => abs($ordenes["precio_unidad"]),
                            "sedeproveedore_id" => $proveedor,
                            "detallearticulo_id" => $ordenCompra->detallearticulo_id,
                            "ordencompra_id" => $ordenCompra->id
        
                            ]);
                    }
                }
                SolicitudCompra::where('id',$request[0]["solicitudcompra_id"])->update(['estado_id'=>$estado,'sedeproveedore_id' => $proveedor]);
            }elseif($flag === 1){
                $message = "Hay cantidades menores o iguales a 0";
                $type = "error";
            }elseif($flag === 2){
                $message = "Hay precios menores o iguales a 0";
                $type = "error";
            }
        }

        return response()->json([
            'message' => $message,
            'type' => $type
        ]);
    }

    public function getSolicitudesEntradas(Bodega $bodega){

        $solicitudesEntradas = SolicitudCompra::select(['solicitud_compras.*', 'sp.Nombre as proveedor', 'sp.id as proveedor_id',
        DB::raw("(select TOP 1 CONCAT(u.name,' ',u.apellido) as usuario_autorizo from ordencompras
        join users as u ON u.id=ordencompras.Usuarioresponde_id
        where ordencompras.solicitudcompra_id =solicitud_compras.id) as usuario")])
            ->with(['ordenCompra' => function($query){
                $query->select(['ordencompras.id','ordencompras.solicitudcompra_id','c.Nombre as medicamento','t.nombre as titular','ordencompras.Cantidad',DB::raw("1 as state"),'da.unidad_compra','ordencompras.cantidad_inicial','hpp.precio_unidad','ordencompras.detallearticulo_id','c.Codigo as codesumi','ordencompras.saldo_cantidad'])
                    ->join('detallearticulos as da','da.id','ordencompras.detallearticulo_id')
                    ->join('codesumis as c','c.id','da.Codesumi_id')
                    ->join('titulares as t','t.id','da.titular_id')
                    ->join('historico_precio_proveedor_medicamentos as hpp','hpp.ordencompra_id','ordencompras.id')
                    ->where('ordencompras.estado_id',7);
            }])
            ->join('sedeproveedores as sp', 'solicitud_compras.sedeproveedore_id', 'sp.id')
            ->where('solicitud_compras.estado_id', 7)
            ->where('solicitud_compras.bodega_id',$bodega->id)
            ->distinct()
            ->get();

        return response()->json($solicitudesEntradas, 200);
    }

    public function getDetallesDisponibles($codesumi,$solicitudcompra){
        $detalle = Detallearticulo::select(['detallearticulos.id',DB::raw("CONCAT(c.Nombre,' ',t.nombre,' (',detallearticulos.Descripcion_Comercial,')') as medicamento")])
            ->join('codesumis as c','c.id','detallearticulos.Codesumi_id')
            ->join('titulares as t','t.id','detallearticulos.titular_id')
            ->where('c.Codigo',$codesumi)
            ->whereNotIn('detallearticulos.id', function ($query) use ($solicitudcompra){
                $query->from('ordencompras')
                    ->select('detallearticulo_id')
                    ->where('solicitudcompra_id',$solicitudcompra);
            })
            ->get()->toArray();
        return response()->json($detalle);
    }
}
