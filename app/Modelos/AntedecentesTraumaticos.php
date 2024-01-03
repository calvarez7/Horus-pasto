<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AntedecentesTraumaticos extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['usuario_id', 'paciente_id', 'traumaticos','accidentes','descripcion_accidentes','descripcion_traumaticos',
    'Citapaciente_id', 'estado_id'
];
}
