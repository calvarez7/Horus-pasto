<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'categoriaproducto_id',
        'estado_id',
        'nombre',
        'descripcion',
        'imagen',
        'presentacion',
        'fecha_vencimiento',
        'codigo_barras',
        'requiere_empaque',
        'valor',
        'adicional',
        'vagout_bodega_id',
        'venta',
        'inventario_id'
    ];

    public function detallecompra()
    {
        return $this->hasMany('App\Modelos\Detallecompra');
    }

    public function categoriaproducto()
    {
        return $this->belongsTo('App\Modelos\Categoriaproducto');
    }

    public function productoInventarios()
    {
        return $this->hasMany('App\Modelos\ProductoInventarios', 'producto_id');
    }

    public function inventario(){
        return $this->belongsTo('App\Modelos\Inventario', 'inventario_id');
    }
}
