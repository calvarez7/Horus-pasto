<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PrecioProveedorMedicamento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'precio_unidad',
        'sedeproveedore_id',
        'detallearticulo_id',
        'iva',
        'iva_facturacion',
        'precio_venta'
    ];
}
