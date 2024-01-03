<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Midiagnostico extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'azucar',
        'cedula',
        'ciclo_vida',
        'clasificacion',
        'colesterol_hdl',
        'diabetes',
        'ejercicio',
        'eres_diabetico',
        'examen_colesterol',
        'fecha_nacimiento',
        'frutas_verduras',
        'fumador',
        'hipertension',
        'imc',
        'perimetro_abdominal',
        'peso',
        'sexo',
        'talla',
        'total_colesterol',
        'trigliceridos',
        'email',
        'presion_sistolica',
        'presion_diastolica'
    ];
}
