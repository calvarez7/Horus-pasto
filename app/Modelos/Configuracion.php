<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $casts = [
        'dia_inicio_habilitacion_validador_rips' => 'integer',
        'dia_final_habilitacion_validador_rips' => 'integer',
        'excepcion_habilitacion_validador_rips' => 'boolean',
        'excepcion_habilitacion_validador_202' => 'boolean',
        'fecha_inicio_habilitacion_validador_202' => 'string',
        'fecha_fin_habilitacion_validador_202' => 'string'
    ];
    protected $table = 'configuraciones';
    protected $guarded = [];
}
