<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipo_solicitud_gestion_humana extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['nombre', 'descripcion', 'estado_id'];
}
