<?php

namespace App\Http\Controllers\Vagout;

use App\Modelos\Inventario;
use App\Modelos\Menu;
use App\Modelos\Producto;
use App\Modelos\ProductoInventarios;
use App\Modelos\ProductoMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\modelos\ciclos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function allProduct()
    {
        $producto = Producto::select([
            'productos.id',
            'productos.codigo_barras',
            'productos.nombre',
            'productos.imagen',
            'productos.presentacion',
            'productos.fecha_vencimiento',
            'productos.requiere_empaque',
            'productos.valor',
            'productos.categoriaproducto_id',
            'productos.descripcion',
            'productos.adicional',
            'ca.nombre as nombrecategoria',
            'ca.descripcion as nombredescripcion',
            'vb.nombre as bodega'
        ])
            ->join('categoriaproductos as ca', 'productos.categoriaproducto_id', 'ca.id')
            ->join('vagout_bodegas as vb','vb.id','productos.vagout_bodega_id')
            ->where('productos.valor','>',0)
            ->where('productos.estado_id',1)
            ->get();

        return response()->json($producto, 200);
    }

    public function all()
    {
         $producto = Producto::select([
             'productos.id',
             'productos.codigo_barras as codigo',
             'productos.nombre',
             'productos.presentacion',
             'productos.requiere_empaque',
             'productos.valor as precio_venta',
             'productos.categoriaproducto_id',
             'productos.adicional',
             DB::raw("CONCAT('producto','') AS tipo")
         ])
             ->get();

        return response()->json($producto, 200);
    }

    public function store(Request $request)
    {
//        dd($request->all());
        $verificarCodigo = Producto::where('codigo_barras',$request->p['codigo_barras'])->first();
        if($verificarCodigo){
            return response()->json(['message' => 'el codigo de barras ya se encuentra registrado'],400);
        }else{
            $idInventario = null;
            if(intval($request->p['vagout_bodega_id']) === 2){
                $inventario = Inventario::create([
                    'stock_actual' => 0,
                    'nombre' => $request->p['nombre'],
                    'unidad_medida' => $request->p['presentacion'],
                    'total_inventario' => 0,
                    'vagout_bodega_id' => $request->p['vagout_bodega_id'],
                    'precio_promedio' => 0
                ]);
                $idInventario = $inventario->id;
            }
            $arrProducto = $request->p;
            $arrProducto['inventario_id'] = $idInventario;
            Producto::create($arrProducto);
            return response()->json([
                'message' => 'Producto Creado con Exito!',
            ], 201);
        }
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update([
            'codigo_barras' => $request->p["codigo_barras"],
            'categoriaproducto_id' => $request->p["categoriaproducto_id"],
            'nombre' => $request->p["nombre"],
            'presentacion' => $request->p["presentacion"],
            'requiere_empaque' =>  $request->p["requiere_empaque"],
            'valor' =>  $request->p["valor"],
            'descripcion' => $request->p["descripcion"],
            'adicional' => $request->p["adicional"]

        ]);


        return response()->json([
            'message' => 'Producto Actualizada con Exito!',
        ], 200);
    }

    public function available(Request $request, Producto $producto)
    {
        $producto->update([
            'Estado_id' => $request->Estado,
        ]);
        return response()->json([
            'message' => 'Estado del Producto Actualizado con Exito!',
        ], 200);
    }

    public function getIngredientesProductos($codigoProducto){
            $ingrediente = Inventario::select(["inventarios.nombre","inventarios.precio_compra","pi.cantidad","inventarios.id"])
                ->join('producto_inventarios as pi','pi.ingrediente_id','inventarios.id')
                ->where('pi.producto_id',$codigoProducto)->get();

        return response()->json($ingrediente, 200);
    }
    public function getMenus(){
        $menus = Menu::all();
        return response()->json($menus, 200);
    }

    public function guardarMenu(Request $request){
        $menu = Menu::where('id',$request->menu['id'])->update(['valor' => $request->menu['valor']]);
        $productos = ProductoMenu::where('menu_id',$request->menu['id'])->where('fecha_receta',$request->fecha_receta)->get()->toArray();
       foreach ($productos as $producto){
           ProductoInventarios::where('producto_id',$producto["producto_id"])->where('fecha_receta',$request->fecha_receta)->delete();
       }
        ProductoMenu::where('menu_id',$request->menu['id'])->where('fecha_receta',$request->fecha_receta)->delete();

        foreach ($request->menu['productos'] as $producto){
            if($producto['id'] === $producto['nombre']){
                $idAnterior = $producto['id'];
                $nuevoProducto = Producto::create(['categoriaproducto_id'=> 1,'estado_id' =>1,'nombre' => $producto['nombre'],'presentacion' => $producto['presentacion'],'codigo_barras' => $producto['codigo'],'valor' => $producto['precio_venta']]);
                $menuProductos = ProductoMenu::where('menu_id', $request->menu['id'])->where('producto_id',$nuevoProducto['id'])->where('fecha_receta',$request->fecha_receta)->first();
                if(!$menuProductos){
                    ProductoMenu::create(['menu_id'=> $request->menu['id'],'producto_id'=> $nuevoProducto['id'],'fecha_receta' => $request->fecha_receta]);
                }
                foreach ($request->ingredientes as $ingrediente) {
                    if($ingrediente['producto'] === $idAnterior){
                            ProductoInventarios::create(['ingrediente_id' => $ingrediente['id'],'producto_id' => $nuevoProducto->id,'cantidad' => $ingrediente['requerida'],'fecha_receta' => $request->fecha_receta]);
                    }
                }
            }else{
                ProductoInventarios::where('producto_id',$producto['id'])->where('fecha_receta',$request->fecha_receta)->delete();
                $menuProductos = ProductoMenu::where('menu_id', $request->menu['id'])->where('producto_id',$producto['id'])->where('fecha_receta',$request->fecha_receta)->first();
                if(!$menuProductos){
                    ProductoMenu::create(['menu_id'=> $request->menu['id'],'producto_id'=> $producto['id'],'fecha_receta' => $request->fecha_receta]);
                }
                foreach ($request->ingredientes as $ingrediente) {
                    if($ingrediente['producto'] === $producto['id']){
                        $ingredientesMenu = ProductoInventarios::where('ingrediente_id',$ingrediente['id'])->where('producto_id',$producto['id'])->where('fecha_receta',$request->fecha_receta)->first();
                        if($ingredientesMenu){
                            $ingredientesMenu->update(['cantidad'=>$ingrediente['requerida']]);
                        }else{
                            ProductoInventarios::create(['ingrediente_id' => $ingrediente['id'],'producto_id' => $producto['id'],'cantidad' => $ingrediente['requerida'],'fecha_receta' => $request->fecha_receta]);
                        }
                    }
                }
            }
        }
        return response()->json(["message" => 'Menu Actualizado!'], 200);
    }
    public function detalleMenu($id, Request $request){
        $menu = Menu::find($id)->toArray();
        $productos =  Producto::select(['productos.*'])
            ->join('productos_menus as pi','productos.id','pi.producto_id')
            ->where('fecha_receta',$request->fecha_receta)
            ->where('pi.menu_id',$id)->get()->toArray();
        $ingrediente = Inventario::select(['inventarios.*','pi.producto_id','pi.cantidad'])
            ->join('producto_inventarios as pi','inventarios.id','pi.ingrediente_id')
            ->where('pi.fecha_receta',$request->fecha_receta)
            ->whereIn('pi.producto_id',function ($q) use ($id){
                $q->from('productos as p')
                    ->select('p.id')
                    ->join('productos_menus as pm','p.id','pm.producto_id')
                    ->where('pm.menu_id', $id);
            })->get()->toArray();
        $data = [
            'menu' => $menu,
            'productos' =>$productos,
            'ingrediente' => $ingrediente
        ];
        return response()->json($data, 200);
    }

    public function disponibilidadMenu(){
        $data = ProductoMenu::select(['productos_menus.fecha_receta','m.nombre'])
            ->join('menus as m','m.id','productos_menus.menu_id')
            ->whereNotNull('fecha_receta')->distinct()->get();
        return response()->json($data);
    }

    public function getProductos($bodega){
        $producto = Producto::select([
            'productos.id',
            'productos.codigo_barras as codigo',
            'productos.nombre',
            'productos.presentacion',
            'productos.requiere_empaque',
            'productos.valor as precio_venta',
            'productos.categoriaproducto_id',
            'productos.adicional',
            'productos.inventario_id',
            'productos.vagout_bodega_id',
            DB::raw("CONCAT('producto','') AS tipo")
        ])
            ->where('productos.vagout_bodega_id',$bodega)
            ->get();

        return response()->json($producto);
    }

    public function ciclos()
    {
        $ciclos = ciclos::all();
        return response()->json($ciclos, 200);
    }
}
