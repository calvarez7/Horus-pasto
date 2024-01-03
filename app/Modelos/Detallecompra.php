<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Detallecompra extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'factura_id',
        'producto_id',
        'cantidad_producto',
        'valor_unitario',
        'descuento',
        'nota',
        'created_at',
        'updated_at',
    ];

}
