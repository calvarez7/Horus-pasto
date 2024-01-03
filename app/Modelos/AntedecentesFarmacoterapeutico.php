<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AntedecentesFarmacoterapeutico extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['tratamientos_cronicos', 'descripcion_tratamientos_cronicos', 'tratamientos_biologicos',
    'descripcion_tratamientos_biologicos', 'quimioterapia', 'descripcion_quimioterapia', 'usuario_id', 'paciente_id',
    'Citapaciente_id', 'estado_id'
    ];
}
