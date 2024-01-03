<?php

namespace App\Http\Controllers\Vagout;

use App\Modelos\Ciclo;
use App\modelos\ciclo_menu_productos;
use App\modelos\ciclos;
use App\modelos\ciclos_menus;
use App\Modelos\Codigomanual;
use App\Modelos\Inventario;
use App\Modelos\Menu;
use App\Modelos\MenuDia;
use App\Modelos\Producto;
use App\Modelos\ProductoInventarios;
use App\modelos\Registro_ciclo_menu;
use App\Modelos\RegistroInventario;
use App\Modelos\VagoutBodega;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\CargaInventarioImport;
use App\Modelos\Rep;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $inventarios = Inventario::select(['inventarios.*','vb.nombre as bodega'])->join('vagout_bodegas as vb','vb.id','inventarios.vagout_bodega_id')->get();
        return response()->json($inventarios, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventario = Inventario::create($request->all());
        return response()->json($inventario);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modelos\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modelos\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modelos\Inventario  $inventario
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Inventario $ingrediente)
    {
        $ingrediente->update($request->all());
        return response()->json($ingrediente);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modelos\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }

    public  function validar(Request $request){
        $unidades= ['Kilogramo','Gramo','Milligramo','Litro','Mililitro','Onza','Unidad','Galon'];
        $noRegistra = [];
        $registra = [];
        (new FastExcel)->import($request->data, function ($line) use (&$noRegistra,&$registra,$unidades,$request) {
                //$data["ingrediente"] = $line['Ingrediente'];
                $data["Proveedor"] = $line["Nombre Proveedor"];
                $data["Nit"] = $line["Nit proveedor"];
                $data["Factura"] = $line["Número de factura"];
                $data["Precio"] = $line["Total"];
                $data["Cantidad"] = $line["Cantidad"];
//                $data["Correo"] = $line["CORREO"];
//                $data["Telefono"] = $line["TELEFONO"];
//                $data["Municipio"] = $line["MUNICIPIO"];
                $data["IVA"] = $line["IVA"];
                $data["unidad"] = $line['Unidad'];
            $ingrediente = null;
                if(intval($request->bodega) === 2){
                    $ingrediente = Inventario::select(['inventarios.*'])
                        ->join('productos as p','inventarios.id','p.inventario_id')
                        ->where('p.codigo_barras',strval($line['Ingrediente']))
                        ->where('inventarios.vagout_bodega_id',intval($request->bodega))
                        ->first();
                }else{
                    $ingrediente = Inventario::where('nombre',$line['Ingrediente'])->where('vagout_bodega_id',$request->bodega)->first();
                }

                if($ingrediente){
                    $cantidadFinal = floatval($ingrediente->stock_actual)+floatval($line["Cantidad"]);
                        $valorFinal = floatval($ingrediente->total_inventario)+(floatval($line["Total"]+(!$line["IVA"]?0:floatval($line["IVA"]))));
                        $promedio = $valorFinal/$cantidadFinal;
                        $ingrediente->update(['stock_actual' => $cantidadFinal,'total_inventario' => $valorFinal,'precio_promedio'=>$promedio,'unidad_medida'=>$line['Unidad']]);

                        RegistroInventario::create([
                            'inventario_id' => $ingrediente->id,
                            'tipo' => 'Entrada',
                            'cantidad' => $line["Cantidad"],
                            'valor' => $line["Total"],
                            'proveedor' => $line["Nombre Proveedor"],
                            'nit' => $line["Nit proveedor"],
                            'numero_factura' => $line["Número de factura"],
                            'iva' => $line["IVA"],
//                        'telefono' => $entrada["Telefono"],
//                        'municipio' => $entrada["Municipio"],
//                        'correo' => $entrada["Correo"],
                    ]);
                }else{
                    $cantidadFinal = floatval($line["Cantidad"]);
                    $valorFinal = floatval($line["Total"]);
                    $promedio = floatval($line["Valor"]);
                    $ingrediente = Inventario::create(['nombre' => $line['Ingrediente'],'stock_actual' => $cantidadFinal,'total_inventario' => $valorFinal,'precio_promedio'=>$promedio,'unidad_medida'=>$line['Unidad'],'vagout_bodega_id' => intval($request->bodega)]);

                    RegistroInventario::create([
                        'inventario_id' => $ingrediente->id,
                        'tipo' => 'Entrada',
                        'cantidad' => $line["Cantidad"],
                        'valor' => $line["Total"],
                        'proveedor' => $line["Nombre Proveedor"],
                        'nit' => $line["Nit proveedor"],
                        'numero_factura' => $line["Número de factura"],
                        'iva' => $line["IVA"],
//                        'telefono' => $entrada["Telefono"],
//                        'municipio' => $entrada["Municipio"],
//                        'correo' => $entrada["Correo"],
                    ]);
                }
        return response()->json($noRegistra);
        });
    }

    public function calcularPrecioProducto($id){
        $productos = Producto::select(["productos.*"])
            ->join('producto_inventarios as pi','pi.producto_id','productos.id')
            ->where('pi.ingrediente_id',$id)
            ->get();

        foreach ($productos as $producto){
            $valorIngredientes = 0;
            $ingredientes = ProductoInventarios::where('producto_id',$producto->id)->get();
            foreach ($ingredientes as $ingrediente){
                $inventario = Inventario::find($ingrediente->ingrediente_id);
                $valorIngredientes = $valorIngredientes+round($inventario->precio_compra*$ingrediente->cantidad);
            }
            $producto->update(['valor' => ($valorIngredientes+$producto->adicional)]);
        }
    }

    public function getBodegasVagout(){
        $bodeagasVagout = VagoutBodega::all();
        return response()->json($bodeagasVagout);
    }

    public function ingredientesDia(){
        $ingredientes = Inventario::select(['inventarios.nombre',DB::raw("SUM(pi.cantidad) AS cantidad"),'inventarios.unidad_medida'])->join('producto_inventarios as pi','pi.ingrediente_id','inventarios.id')
            ->where('fecha_receta',date("Y-m-d"))
            ->groupBy('inventarios.nombre','inventarios.unidad_medida')
            ->get()->toArray();
        return response()->json($ingredientes);
    }

    public function salidaMasiva($personas){
        $ingredientes = Inventario::select(['inventarios.id','inventarios.nombre',DB::raw("SUM(pi.cantidad) AS cantidad")])->join('producto_inventarios as pi','pi.ingrediente_id','inventarios.id')
            ->where('fecha_receta',date("Y-m-d"))
            ->groupBy('inventarios.nombre','inventarios.id')
            ->get()->toArray();
        if(count($ingredientes) > 0){
            $registro = RegistroInventario::where('inventario_id',$ingredientes[0]['id'])->where('tipo','Salida')
                ->whereBetween('created_at', [date("Y-m-d").' 00:00:00.000',date("Y-m-d").' 23:59:00.000'])->first();
            if(!$registro){
                foreach ($ingredientes as $ingrediente){
                    $inventario = Inventario::find($ingrediente["id"]);
                    $stockActual = (is_numeric($inventario->stock_actual)?$inventario->stock_actual:0)-intval($ingrediente['cantidad'])*intval($personas);
                    $valorIngredientes = intval($inventario->precio_promedio)*(intval($ingrediente['cantidad'])*intval($personas));
                    $total = intval($inventario->precio_promedio)*$stockActual;
                    $inventario->update(['stock_actual'=>$stockActual,'total_inventario'=>$total]);
                    RegistroInventario::create([
                        'inventario_id' => $ingrediente["id"],
                        'tipo' => 'Salida',
                        'cantidad' => intval($ingrediente['cantidad'])*intval($personas),
                        'valor' => $valorIngredientes
                    ]);
                }
                return response()->json(['message'=>'salida realizada']);
            }else{
                return response()->json(['message'=>'La salida del inventario ya fue realizada']);
            }
        }else{
            return response()->json(['message'=>'Sin registro de ingredientes']);
        }
    }

    public function getCiclos($ciclo){
            $ciclosDia = Inventario::select([
                'inventarios.nombre',
                'inventarios.unidad_medida',
                'pi.cantidad',
                'p.id',
                'p.nombre as producto',
                'm.nombre as menu',
            ])
                ->join('producto_inventarios as pi','inventarios.id','pi.ingrediente_id')
                ->join('productos as p','pi.producto_id','p.id')
                ->join('ciclo_menu_productos as cmp','p.id','cmp.producto_id')
                ->join('ciclos_menus as cm','cmp.ciclo_menu_id','cm.id')
                ->join('ciclos as c','cm.ciclo_id','c.id')
                ->join('menus as m','m.id','cm.menu_id')
                ->where('c.id',$ciclo)
                ->get()->toArray();

            $meduDia = [];
            foreach ($ciclosDia as $cicloDia){
                $meduDia[$cicloDia["menu"]][] = [
                    'nombre' => $cicloDia["nombre"],
                    'unidad' => $cicloDia["unidad_medida"],
                    'cantidad' => $cicloDia["cantidad"],
                    'producto' => $cicloDia["producto"]
                ];
            }
        return response()->json($meduDia);

    }

    public function generarCicloDia(Request $request){
        $registro = Registro_ciclo_menu::create([
            'numero_personas' => $request->comensales,
            'ciclo' => $request->ciclo,
            'fecha' => date('Y-m-d'),
        ]);
        return response()->json(["message"=> 'Ciclo registrado']);
    }

    public function cicloActual(){
        $meduDia = [];
        $personas = 0;
        $registro = Registro_ciclo_menu::where('fecha',date('Y-m-d'))->first();
        if($registro){
            $personas = $registro->numero_personas;
            $ciclosDia = Inventario::select([
                'inventarios.nombre',
                'inventarios.unidad_medida',
                'pi.cantidad',
                'p.id',
                'p.nombre as producto',
                'm.nombre as menu',
            ])
                ->join('producto_inventarios as pi','inventarios.id','pi.ingrediente_id')
                ->join('productos as p','pi.producto_id','p.id')
                ->join('ciclo_menu_productos as cmp','p.id','cmp.producto_id')
                ->join('ciclos_menus as cm','cmp.ciclo_menu_id','cm.id')
                ->join('ciclos as c','cm.ciclo_id','c.id')
                ->join('menus as m','m.id','cm.menu_id')
                ->where('c.id',$registro->ciclo)
                ->get()->toArray();

            foreach ($ciclosDia as $cicloDia){
                $meduDia[$cicloDia["menu"]][] = [
                    'nombre' => $cicloDia["nombre"],
                    'unidad' => $cicloDia["unidad_medida"],
                    'cantidad' => $cicloDia["cantidad"],
                    'producto' => $cicloDia["producto"]
                ];
            }
        }
        return response()->json(['ingredientes'=>$meduDia,'personas'=>$personas]);

    }

    public function cicloActualExcel(){

        $ciclosDia = Inventario::select([
            'c.id as ciclo_menu',
            'm.nombre as tipo_plato',
            'p.nombre as nombre',
            'inventarios.nombre as ingrediente',
            DB::raw("(pi.cantidad*rcm.numero_personas) as cantidad"),
            'inventarios.unidad_medida',
            'rcm.fecha'
        ])
            ->join('producto_inventarios as pi','inventarios.id','pi.ingrediente_id')
            ->join('productos as p','pi.producto_id','p.id')
            ->join('ciclo_menu_productos as cmp','p.id','cmp.producto_id')
            ->join('ciclos_menus as cm','cmp.ciclo_menu_id','cm.id')
            ->join('ciclos as c','cm.ciclo_id','c.id')
            ->join('menus as m','m.id','cm.menu_id')
            ->join('registro_ciclo_menu as rcm','rcm.ciclo','c.id')
            ->where('rcm.fecha',date('Y-m-d'))
            ->get()->toArray();
        return (new FastExcel($ciclosDia))->download('file.xlsx');

    }

    public function descargue(Request $request){
        $ciclos = [];
        $sinCupo = [];
        $sumatoria = [];
        (new FastExcel)->import($request->data, function ($line) use (&$ciclos,&$sumatoria)  {
            if($line["CICLO DE MENÚ"]){
                $ciclos[] = $line;
                $i = Inventario::where('nombre',$line["INGREDIENTES"])->first();
                if($i){
                    if(isset($sumatoria[$line["INGREDIENTES"]])){
                        $sumatoria[$line["INGREDIENTES"]] = $sumatoria[$line["INGREDIENTES"]]+floatval($line['Cantidad']);
                    }else{
                        $sumatoria[$line["INGREDIENTES"]] = floatval($line['Cantidad']);
                    }
                }


            }
        });
        foreach ($sumatoria as $key => $data){
            $i = Inventario::where('nombre',$key)->first();
            $cantidadFinal = floatval($i->stock_actual)-floatval($data);
                    if($cantidadFinal < 0){
                       $ids = array_keys(array_column($ciclos,'INGREDIENTES'),$key);
                       foreach ($ids as $id){
                           $sinCupo[] =  $ciclos[$id];
                       }

                    }
        }
        if(count($sinCupo) === 0){
            $registro = Registro_ciclo_menu::where('fecha',$ciclos[0]["Fecha"]->format('Y-m-d'))->first();
            if($registro){
                return response()->json(['message'=>'El descargue del dia '.$ciclos[0]["Fecha"]->format('Y-m-d').' ya fue realizado','state'=>1]);
            }
            $c = Ciclo::create(['nombre'=>$ciclos[0]["CICLO DE MENÚ"]]);
            Registro_ciclo_menu::create(['ciclo_id'=>$c->id,'fecha'=>$ciclos[0]["Fecha"]->format('Y-m-d'),'numero_personas'=>1]);
            foreach ($ciclos as $ciclo){
                $menu = Menu::where('nombre',$ciclo["TIPO DE PLATO"])->first();
                if(!$menu){
                    $menu = Menu::create(['nombre'=>$ciclo["TIPO DE PLATO"]]);
                }
                $cicloMenu = ciclos_menus::where('ciclo_id',$c->id)->where('menu_id',$menu->id)->first();
                if(!$cicloMenu){
                    $cicloMenu = ciclos_menus::create(['ciclo_id'=>$c->id,'menu_id'=>$menu->id]);
                }
                $producto = Producto::where('nombre',$ciclo["NOMBRE"])->first();
                if(!$producto){
                    $producto = Producto::create(['nombre'=>$ciclo["NOMBRE"],'venta'=>0,'vagout_bodega_id'=>1,'categoriaproducto_id'=>6]);
                }
                $cicloMenuProductos = ciclo_menu_productos::where('ciclo_menu_id',$cicloMenu->id)->where('producto_id',$producto->id)->first();
                if(!$cicloMenuProductos){
                    $cicloMenuProductos = ciclo_menu_productos::create(['ciclo_menu_id'=>$cicloMenu->id,'producto_id'=>$producto->id]);
                }
                $ingrediente = Inventario::where('nombre',$ciclo["INGREDIENTES"])->first();
                if(!$ingrediente){
                    $ingrediente = Inventario::create(['nombre'=>$ciclo["INGREDIENTES"],'vagout_bodega_id'=>1,'stock_actual' => 0,'total_inventario' => 0]);
                }else{
                    $cantidadFinal = floatval($ingrediente->stock_actual)-floatval($ciclo['Cantidad']);
                    $total = floatval($ingrediente->precio_promedio)*$cantidadFinal;
                    if($cantidadFinal >= 0 && $total >= 0){
                        $ingrediente->update(['stock_actual' => $cantidadFinal,'total_inventario' => $total]);
                        RegistroInventario::create([
                            'inventario_id' => $ingrediente->id,
                            'tipo' => 'Salida',
                            'cantidad' => $cantidadFinal,
                            'valor' => $total
                        ]);
                    }
                }
                $productoInventario = ProductoInventarios::create(['ciclo_menu_producto_id'=>$cicloMenuProductos->id,'ingrediente_id'=>$ingrediente->id,'cantidad'=>$ciclo["Cantidad"]]);
            }
            return response()->json(['message'=>'Descargue exitoso','state'=>0],200);
        }else{
            return response()->json(['message'=>'Error Descargue','state'=>2,'sinCupo' => $sinCupo],200);
        }
    }

    public function cargueInventario(Request $request)
    {
        $bodega = intval($request->bodega);
        Inventario::where('vagout_bodega_id', intval($bodega))->update(['stock_actual' => 0]);
        (new FastExcel)->import($request->data, function ($line) use($bodega) {
            $inventario = null;
            if($bodega === 1){
                $i = Inventario::where('nombre',$line['codigo'])->where('vagout_bodega_id')->first();
                if($i){
                    $inventario = $i;
                }else{
                    $inventario = Inventario::create([
                        'stock_actual' => $line['existencias'],
                        'nombre' => $line['codigo'],
                        'vagout_bodega_id' => 1,
                        'total_inventario' => floatval($line['unitario'])*floatval($line['existencias']),
                        'unidad_medida' => $line['medida'],
                        'precio_promedio' => floatval($line['unitario'])
                    ]);
                }
            }else{
                $inventario = Inventario::select(['inventarios.*'])->join('productos as p','inventarios.id','p.inventario_id')
                    ->where('p.codigo_barras',$line['codigo'])->first();
            }
            if($inventario){
                $inventario->update(['stock_actual' => $line['existencias'],'unidad_medida'=> $line['medida']]);
                RegistroInventario::create([
                    'inventario_id' => $inventario->id,
                    'tipo' => 'Ajuste por Inventario',
                    'cantidad' => $line['existencias'],
                    'unidad_medida' => $line['medida'],
                    'valor' => 0,
                ]);
            }
        });
    }

    public function getInventario(Request $request, $id)
    {
        $inventario = Inventario::where('id',$id )->first();

        if ($request->tiposInventario == "Ajuste Entrada" ){
            if($inventario->stock_actual >= 0 ) {
                $inventario->stock_actual += $request->cantidad;
            }else{
                $inventario->stock_actual = abs($inventario->stock_actual) + intval($request->cantidad);
            }
        }elseif ($request->tiposInventario == "Ajuste Salida" && $request->cantidad  <= $inventario->stock_actual && $request->cantidad > 0)  {
            $resta = intval($inventario->stock_actual) - intval($request->cantidad);
            $inventario->stock_actual = $resta;
        }
        $inventario->save();
        RegistroInventario::create([
            'inventario_id' => $inventario->id,
            'tipo' => $request->tiposInventario,
            'cantidad' => $request->cantidad,
            'valor' => 0,
            'descripcion' => $request->descripcionAjuste
        ]);

        return response()->json(['message'=>'Ajuste Realizado']);
    }
}
