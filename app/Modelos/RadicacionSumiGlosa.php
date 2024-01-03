<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class RadicacionSumiGlosa extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'glosa_id',
        'user_id',
        'repuesta_sumimedical',
        'estado_id',
        'valor_aceptado',
        'valor_no_aceptado',
        'acepta_ips',
        'levanta_sumi',
        'no_acuerdo'
    ];
}
