<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class notaenfermeria extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'cita_paciente_id',
        'nota',
        'signos_alarma',
        'cuidados_casa',
        'caso_urgencia',
        'alimentacion',
        'efectos_secundarios',
        'habito_higiene',
        'derechos_deberes',
        'normas_sala_quimioterapia',
    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente', 'cita_paciente_id');
    }
}
