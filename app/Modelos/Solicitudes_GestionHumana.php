<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Solicitudes_GestionHumana extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['tipo_solicitud_gestion_humana_id', 'user_id', 'descripcion', 'area_id', 'estado_id'];
}
