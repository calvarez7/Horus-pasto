<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class TipoSolicitud extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'tipos_solicitud';

    protected $fillable = ['nombre', 'descripcion', 'estado_id', 'opcion1', 'opcion2'];
}
