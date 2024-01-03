<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Respuestatutela extends Model
{

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Tutela_id', 'User_id', 'Respuesta', 'Responsable','tipoactuacion'
    ];

    public function Adjuntos_respuestas()
    {
        return $this->hasMany('App\Modelos\Adjuntos_tutelas', 'Respuesta_id');
    }
}
