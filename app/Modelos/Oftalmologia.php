<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Oftalmologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'cita_paciente_id',
        'nombre_responsable',
        'documento_responsable',
        'AVCC_correcion_derecho',
        'AVCC_correcion_izquierdo',
        'AVCC_sincorrecion_derecho',
        'AVCC_sincorrecion_izquierdo',
        'ph_derecho',
        'ph_izquierdo',
        'motilidad_derecho',
        'motilidad_izquierdo',
        'covertest_derecho',
        'covertest_izquierdo',
        'p_intraocular_derecho',
        'p_intraocular_izquierdo',
        'gonioscopia_derecho',
        'gonioscopia_izquierdo',
        'fondo_ojo_derecho',
        'fondo_ojo_izquierdo',
    ];
}
