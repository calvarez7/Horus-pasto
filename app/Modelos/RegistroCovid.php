<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class RegistroCovid extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre', 'entidad', 'paciente', 'ruta'
    ];
}
