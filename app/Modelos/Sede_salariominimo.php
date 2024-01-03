<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Sede_salariominimo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'sedeproveedor_id', 'salariominimo_id', 'estado_id'
    ];
}
