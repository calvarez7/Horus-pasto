<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Remisionprogramas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'primerainfancia',
        'infancia',
        'adolescencia',
        'juventud',
        'adultez',
        'vejez',
        'maternoperinatal',
        'preconcepcional',
        'psicoprofilactico',
        'educativo',
        'lactanciamaterna',
        'cancerdecervix',
        'cancerdemama',
        'cancercolorectal',
        'cancerdeprostata',
        'vacunacion',
        'controlpospartoyreciennacido',
        'planificacionfamiliar',
        'riesgocardiovascular',
        'otrogrupoderiesgo',
        'cual',
        'user_id',
        'paciente_id'
    ];
}
