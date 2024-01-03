<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Gestion_solicitud extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['solicitud_gestionhumana_id', 'estado_id', 'user_id', 'respuesta_solicitud_id'];
}
