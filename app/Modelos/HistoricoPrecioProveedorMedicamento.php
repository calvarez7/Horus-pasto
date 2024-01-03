<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class HistoricoPrecioProveedorMedicamento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'precio_unidad',
        'sedeproveedore_id',
        'detallearticulo_id',
        'ordencompra_id'
    ];
}
