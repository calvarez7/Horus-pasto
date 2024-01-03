<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ProductosCompuesto extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'producto_id',
        'producto_detalle_id',
        'nombre',
        'descripcion',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Modelos\Producto');
    }
}
