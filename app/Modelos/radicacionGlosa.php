<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class radicacionGlosa extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'glosa_id',
        'prestador_id',
        'repuesta_prestador',
        'repuesta_sumimedical',
        'estado_id',
        'archivo',
        'codigo',
        'valor_aceptado',
        'valor_no_aceptado'
    ];
}
