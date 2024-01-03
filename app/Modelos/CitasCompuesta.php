<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CitasCompuesta extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['cita_paciente_id','cita_paciente_relacion_id'];
}
