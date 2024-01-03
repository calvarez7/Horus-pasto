<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Plantilla_imagenologia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre', 'indicacion', 'tecnica', 'hallazgos', 'conclusion',
        'notaclaratoria'
    ];
}
