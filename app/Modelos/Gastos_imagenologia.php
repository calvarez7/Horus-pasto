<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Gastos_imagenologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'bodegaimagenologia_id', 'user_id', 'estado_id', 'cantidad', 'cita_paciente_id', 'actualizo_userid'
    ];

    protected $table = 'gastos_imagenologia';
}
