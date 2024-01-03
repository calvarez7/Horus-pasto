<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Respuesta_solicitud extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['descripcion', 'estado_id', 'user_id'];
}
