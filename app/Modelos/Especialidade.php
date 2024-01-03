<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Primeravez_id', 'Control_id', 'estado',
    ];

    public function cups()
    {
        return $this->hasMany('App\Modelos\Cup');
    }

    public function tipoagendas()
    {
        return $this->hasMany('App\Modelos\TipoAgenda');
    }
}
