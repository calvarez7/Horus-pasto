<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Codigodanecolegio', 'Municipio_id', 'Estado_id',
    ];

    public function paciente()
    {
        return $this->hasMany('App\Modelos\Paciente');
    }
}
