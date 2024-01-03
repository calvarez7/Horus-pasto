<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Antecedente extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Esfamiliar',
    ];

    public function paciente()
    {
        return $this->hasMany('App\Modelos\Paciente');
    }

    public function parentesco()
    {
        return $this->hasMany('App\Modelos\Parentesco');
    }
}
