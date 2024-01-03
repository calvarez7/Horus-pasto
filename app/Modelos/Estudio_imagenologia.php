<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Estudio_imagenologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['cita_paciente_id', 'cantidad', 'via', 'peso', 'tiempo',
        'exposicion', 'mas', 'kv', 'distancia', 'foco', 'total_dosis', 'observacion', 'user_id'
    ];
}
