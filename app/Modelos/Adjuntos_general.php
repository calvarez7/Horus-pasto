<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Adjuntos_general extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'tipo_id', 'nombre', 'ruta', 'cita_id', 'user_id', 'prestador_id',
    ];
}
