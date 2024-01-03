<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Parentescoantecedente extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Antecedente_id', 'Citapaciente_id', 'Usuario_id', 'Descripcion', 'parentesco_id', 'cie10_id'
    ];

    public function paciente()
    {
        return $this->hasMany('App\Modelos\Paciente');
    }

    public function parentesco()
    {
        return $this->hasMany('App\Modelos\Parentesco');
    }

    public function Antecedente()
    {
        return $this->hasMany('App\Modelos\Antecedente');
    }

    public function user()
    {
        return $this->hasMany('App\User');
    }
}
