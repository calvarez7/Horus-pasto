<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Codigo_glosas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'codigo', 'descripcion', 'tipo_id', 'estado_id'
    ];
}
