<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Asignar_solicitud extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    Protected $fillable = ['solicitudes_gestionhumana_id', 'estado_id', 'user_id'];
}
