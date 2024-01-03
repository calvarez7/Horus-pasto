<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Especialidadtipoagenda extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Especialidad_id', 'Tipoagenda_id', 'Duracion', 'Primeravez_id', 'Control_id','estado_id'
    ];

    protected $table = "especialidade_tipoagenda";

    public function agendas()
    {
        return $this->hasMany('App\Modelos\Agenda');
    }

}
