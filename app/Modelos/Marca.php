<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Citapaciente_id', 'Nivel', 'Marca'];

    public function paciente()
    {
        return $this->belongsTo('App\Modelos\Paciente');
    }
}
