<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'producto_id', 'precio_compra', 'precio_venta', 'stock_minimo', 'stock_actual', 'fecha_produccion','nombre','total_inventario','unidad_medida','vagout_bodega_id',
        'precio_promedio'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Modelos\Producto', 'id');
    }
}
