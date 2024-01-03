<?php

namespace App\Http\Controllers\Vagout;

use App\Formats\FacturaVagout;
use App\Modelos\Inventario;
use App\Modelos\Producto;
use App\Modelos\ProductoInventarios;
use App\Modelos\ProductoMenu;
use App\modelos\Registro_ciclo_menu;
use App\Modelos\RegistroInventario;
use App\User;
use App\Modelos\Factura;
use App\Modelos\Empleado;
use App\Modelos\Paciente;
use Illuminate\Http\Request;
use App\Modelos\Detallecompra;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modelos\Rep;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseStatusCodeSame;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $facturas = Factura::select([
            'facturas.id',
            DB::raw("(CASE WHEN facturas.paciente_id is null THEN CONCAT(u.Nombre,' (',u.Documento,') (Empleado)') ELSE CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom,' (',p.Num_Doc,') (Paciente)') END) as usuario"),
            'tp.nombre as tipo',
            'facturas.valor_total as total',
            'facturas.estado_id',
            'facturas.created_at as fechaCreacion'
        ])->leftjoin('pacientes as p','facturas.paciente_id','p.id')
            ->leftjoin('empleados as u','facturas.usuario_id','u.id')
            ->join("tipoFacturas as tp","tp.id","facturas.tipofactura_id");
            if($request->identificacion != ""){
                $facturas->where('u.Documento',$request->identificacion)
                    ->orWhere('p.Num_Doc',$request->identificacion);
            }
            if($request->fechaActual === true){
                $actuaDesde = date("Y-m-d")." 00:00:00.000";
                $actuaHasta = date("Y-m-d")." 23:59:59.999";

                $facturas->whereBetween('facturas.created_at',[$actuaDesde,$actuaHasta]);
            }else{
                $facturas->whereBetween('facturas.created_at',[$request->fechaDesde." 00:00:00.000",$request->fechaHasta." 23:59:59.999"]);
            }
            if(intval($request->estados) > 0){
                $facturas->where('facturas.estado_id',$request->estados);
            }
        return response()->json($facturas->get(), 200);

    }
    public function update(Request $request, Factura $factura)
    {
        $factura->update($request->all());

        return response()->json([
            'message' => 'Factura Actualizada!'
        ], 200);

    }
    public function saveTypeSale(Request $request){
        $mensaje = 'Factura Generada';
        $type = 'success';
        $flag = 0;
        $domicilio = 0;
        $empaque = 0;
        $cantidadEm = 0;
        $factura = new Factura();
        if($request->tipoCliente == "Empleado"){
            $empleado = Empleado::where('Documento',$request->cliente)->first();
            $factura->usuario_id = $empleado->id;
        }else{
            $paciente = Paciente::where('Num_Doc',$request->cliente)->first();
            $factura->paciente_id = $paciente->id;
        }

        if($request->formaPago === "Descuento Nomina"){
            $totalDeuda = Factura::select(DB::raw('SUM(valor_total) as deuda'))->where('forma_pago','Descuento Nomina')->where('estado_id',7)->where('usuario_id',$empleado->id)->first();
            $valorPorcentaje = ((intval($empleado->salario)*20)/100);
            $deudaActual = intval($totalDeuda->deuda) + intval($request->valor)+$domicilio+($empaque*$cantidadEm);
            if($deudaActual > $valorPorcentaje){
                $flag = 1;
                $mensaje = "Cupo Excedido";
                $type = "error";
            }
        }elseif ($request->formaPago === "Efectivo"){
            $factura->total_efectivo = $request->efectivo;
        }

        if($flag === 0){
        if($request->tipoEntrega == "Autoservicio"){
            $factura->estado_id = 7;
            $factura->tipofactura_id = 1;
            $factura->valor_domicilio = 0;
        }else{
            $factura->estado_id = 18;
            $factura->tipofactura_id = 3;
            $factura->valor_domicilio = intval($request->valorDomicilio);
            $empaque = intval($request->valorEmpaque);
            $cantidadEm = intval($request->cantidadEmpaques);
            $factura->valor_empaque = $empaque;
            $factura->cantidad_empaques= $cantidadEm;
            $domicilio = intval($request->valorDomicilio);
        }
        $factura->forma_pago = $request->formaPago;
        $factura->valor_total = intval($request->valor)+$domicilio+($empaque*$cantidadEm);
        $factura->save();
        foreach($request->productos as $producto){
            if($producto['tipo'] === 'menu'){
            $productosMenu = Producto::select([
                'productos.codigo_barras AS id',
                'productos.nombre AS nombre',
                'productos.valor AS precio',
                'productos.id AS idInterno',
                DB::raw("CONCAT('','0') AS empaque")
                ])->join('productos_menus as pm','pm.producto_id','productos.id')
                    ->where('menu_id',$producto['idInterno'])
                    ->get()->toArray();
                foreach($productosMenu as $productoMenu){

                    $facturaDetalle = new Detallecompra();
                    $facturaDetalle->factura_id = $factura->id;
                    $facturaDetalle->producto_id = $productoMenu["idInterno"];
                    $facturaDetalle->cantidad_producto = $producto["cantidad"];
                    $facturaDetalle->valor_unitario = $productoMenu["precio"];
                    $facturaDetalle->save();
                }
            }else{
                $facturaDetalle = new Detallecompra();
                $facturaDetalle->factura_id = $factura->id;
                $facturaDetalle->producto_id = $producto["idInterno"];
                $facturaDetalle->cantidad_producto = $producto["cantidad"];
                $facturaDetalle->valor_unitario = $producto["precio"];
                $facturaDetalle->save();
                if(intval($producto["bodega"]) === 2){
                    if($producto["inventario"] && $producto["inventario"] !== 'null'){
                        $inventario = Inventario::find($producto["inventario"]);
                        $cantidadRestante = floatval($inventario->stock_actual) - floatval($producto["cantidad"]);
                        $inventario->update(['stock_actual' => $cantidadRestante]);
                        RegistroInventario::create([
                            'inventario_id' => $producto["inventario"],
                            'tipo' => 'Salida',
                            'cantidad' => $producto["cantidad"],
                            'valor' => intval($producto["precio"])*intval($producto["cantidad"])
                        ]);
                    }
                }
            }

        }
    }
//        try {
//            $FacturaVagout = new FacturaVagout();
//            $data = ['order_id' => $factura->id,'sendmail' => true];
//            $FacturaVagout->generar($data);
//        }catch (\Exception $e){
//
//        }


        $data = [
            "message" => $mensaje,
            "type" => $type,
            "factura" => $factura->id
        ];
        return response()->json($data);
    }

    public function registroInventario(Request $request)
    {
        $informe = [];
        switch ($request->id){
            case 1:
                $informe = RegistroInventario::select([
                    'created_at AS FECHA',
                    'numero_factura AS N°FRA',
                    'nit AS NIT',
                    'proveedor AS PROVEEDOR',
                    DB::raw("CONCAT('','') AS DIRECCION"),
                    'municipio AS MUNICIPIO',
                    DB::raw("CONCAT('COLOMBIA','') AS PAIS"),
                    DB::raw("CONCAT('TERCIARIA','') AS [ACTIVIDAD ECONOMICA]"),
                    'correo AS CORREO ELECTRONICO',
                    'telefono AS TELEFONO',
                    'valor as BRUTO',
                    'iva as IVA',
                    DB::raw("(valor+((valor*iva)/100)) AS TOTAL"),
                ])
                    ->where('created_at','>=',$request->fechainicio.' 00:00:00')
                    ->where('created_at','<=',$request->fechafin.' 23:59:00')
                    ->where('registro_inventarios.tipo','Entrada')
                    ->get();
                break;
            case 2:
                $informe = RegistroInventario::select([
                    'registro_inventarios.created_at AS Fecha',
                    'registro_inventarios.numero_factura AS Documento Ref',
                    'registro_inventarios.nit AS Nit Proveedor',
                    'registro_inventarios.proveedor AS Nombre Proveedor',
                    'i.nombre AS Concepto Compra',
                    'registro_inventarios.valor AS Bruto',
                    'registro_inventarios.iva AS IVA',
                    DB::raw("(registro_inventarios.valor+((registro_inventarios.valor*registro_inventarios.iva)/100)) AS Total"),
                    DB::raw("CONCAT('COMPUTADOR','') AS [TIPO FRA]"),
                ])
                    ->join('inventarios as i','i.id','registro_inventarios.inventario_id')
                    ->where('registro_inventarios.created_at','>=',$request->fechainicio.' 00:00:00')
                    ->where('registro_inventarios.created_at','<=',$request->fechafin.' 23:59:00')
                    ->where('registro_inventarios.tipo','Entrada')
                    ->get();
                break;
            case 3:
                $informe = Factura::select([
                    'dc.id as Consecutivo',
                    'dc.created_at as Fecha',
                    'vb.nombre as PuntoVenta',
                    'e.Documento as Documento',
                    'p.nombre as Nombre',
                    'p.descripcion as Descripcion',
                    'dc.cantidad_producto as Cantidad',
                    DB::raw("((dc.valor_unitario*dc.cantidad_producto)-(((dc.valor_unitario*dc.cantidad_producto)*8)/100)) as Valor"),
                    DB::raw("('0')as IVA"),
                    DB::raw("(((dc.valor_unitario*dc.cantidad_producto)*8)/100) as ImpuestoConsumo"),
                    DB::raw("(dc.valor_unitario*dc.cantidad_producto) as Total"),
                    'dc.cantidad_producto as Cantidad',
                    'facturas.forma_pago as MedioPago',
                    'e.correo as CorreoElectronico'

                ])
                    ->join('empleados as e','e.id','facturas.usuario_id')
                    ->join('detallecompras as dc','dc.factura_id','facturas.id')
                    ->join('productos as p','p.id','dc.producto_id')
                    ->join('vagout_bodegas as vb','vb.id','p.vagout_bodega_id')
                    ->where('facturas.created_at','>=',$request->fechainicio.' 00:00:00')
                    ->where('facturas.created_at','<=',$request->fechafin.' 23:59:00')
                    ->get();
                break;
            case 4:
                $informe = RegistroInventario::select([
                    'registro_inventarios.created_at AS Fecha',
                    'i.nombre AS COMPONENTE',
                    'registro_inventarios.cantidad AS CANT.',
                    'i.Unidad_medida AS UNIDAD MEDIDA',
                    'registro_inventarios.valor AS COSTO',
                    DB::raw("(registro_inventarios.valor*registro_inventarios.cantidad) AS [TOTAL CONSUMO]"),
                ])
                    ->join('inventarios as i','i.id','registro_inventarios.inventario_id')
                    ->where('registro_inventarios.created_at','>=',$request->fechainicio.' 00:00:00')
                    ->where('registro_inventarios.created_at','<=',$request->fechafin.' 23:59:00')
                    ->where('registro_inventarios.tipo','Salida')
                    ->get();
                break;
            case 5:
                $informe = Inventario::select([
                    'id AS REFERENCIA',
                    'nombre AS PRODUCTO',
                    'stock_actual AS CANTIDAD',
                    'unidad_medida AS UNIDAD MEDIDA',
                    'precio_compra AS COSTO',
                    'total_inventario AS TOTAL'
                ])->get();
                break;
            case 6:
                $informe = Registro_ciclo_menu::select([
                    'c.nombre as ciclo',
                    'm.nombre as plato',
                    'p.nombre as nombre',
                    'i.nombre as ingrediente',
                    'pi.cantidad',
                    'i.unidad_medida'
                ])->join('ciclos as c','registro_ciclo_menu.ciclo_id','c.id')
                    ->join('ciclos_menus as cm','c.id','cm.ciclo_id')
                    ->join('ciclo_menu_productos as cmp','cm.id','cmp.ciclo_menu_id')
                    ->join('productos as p','cmp.producto_id','p.id')
                    ->join('producto_inventarios as pi','cmp.id','pi.ciclo_menu_producto_id')
                    ->join('inventarios as i','pi.ingrediente_id','i.id')
                    ->join('menus as m','cm.menu_id','m.id')
                    ->where('registro_ciclo_menu.fecha',$request->fechainicio)
                    ->get();
                break;
            case 7:
                $informe = Factura::select([
                    'e.Documento',
                    'e.Nombre as Empleado',
                    DB::raw("SUM(valor_total) as total")
                ])->join('empleados as e','e.id','facturas.usuario_id')
                ->where('facturas.created_at','>=',$request->fechainicio)
                ->where('facturas.created_at','<=',$request->fechafin)
                ->whereIn('facturas.estado_id', [7,6])
                ->where('facturas.forma_pago','Descuento Nomina')
                ->groupBy('e.Documento','e.Nombre')
                ->get();

                break;

        }
        return (new FastExcel($informe))->download('file.xls');
    }

    public function generarCorte(){

      $fechaDesde = date('Y-m').'-01';
      $fechasHasta = date('Y-m-t');
      $facturaFecha = Factura::where('fecha_corte','>=',$fechaDesde)
      ->where('fecha_corte','<=',$fechasHasta)->first();

        if (!$facturaFecha) {
            $fechaCorte = date('Y-m-d');
            Factura::whereNull('fecha_corte')
            ->update(['fecha_corte' => $fechaCorte]);

            $facturasCortes = collect(Factura::select('e.Nombre as NOMBRE EMPLEADO', 'e.Documento as CC',
             'facturas.valor_total as VALOR TOTAL', 'facturas.fecha_corte as FECHA DE CORTE', )
            ->join('empleados as e','facturas.usuario_id','e.id')
            ->where('fecha_corte',$fechaCorte)
            ->where('tipofactura_id', 1 )
            ->where('facturas.estado_id',4)
            ->where('observacion_final','deducido de nomina')
            ->get()->toArray());

            Factura::where('facturas.estado_id',4)
            ->update(['facturas.estado_id' => 7]);

            return (new FastExcel($facturasCortes))->download('file.xls');
        }
        else {
            return response()->json(['message'=>'REPORTE YA GENERADO'], 400);
        }
    }

    public function seleccionarFechaCorte()
    {
        $seleccionarFecha = Factura::select('fecha_corte')
        ->where('fecha_corte', '!=',null)
        ->groupBy('fecha_corte')
        ->get();
        return response()->json($seleccionarFecha);
    }

    public function descargarCorteFecha(Request $request)
    {
        $fechaCortes = collect(Factura::select('e.Nombre as NOMBRE EMPLEADO', 'e.Documento as CC',
        'facturas.valor_total as VALOR TOTAL', 'facturas.fecha_corte as FECHA DE CORTE', )
        ->join('empleados as e','facturas.usuario_id','e.id')
        ->where('fecha_corte',$request->prueba)
        ->where('facturas.estado_id',7)
        ->get()->toArray());

        return (new FastExcel($fechaCortes))->download('file.xls');
    }
}
