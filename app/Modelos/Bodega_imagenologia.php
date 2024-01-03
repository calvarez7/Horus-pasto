<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Bodega_imagenologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre', 'grupo_id', 'estado_id', 'user_id'
    ];
}
