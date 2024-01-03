<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Notastransacion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['usuario_id', 'Movimiento_id', 'Nombre', 'Descripcion'];
}
