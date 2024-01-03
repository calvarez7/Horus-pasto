<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Conducta extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Citapaciente_id', 'Planmanejo', 'Recomendaciones', 'usuario_id','paciente_id', 'Destinopaciente',
        'Finalidad','Especialidad','cursoprofilactico','consultaExterna',
        'asesoriaive'
    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
