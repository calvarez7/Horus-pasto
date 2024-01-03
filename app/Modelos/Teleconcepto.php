<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Teleconcepto extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $guarded = [];

    public function cie10s()
    {
        return $this->belongsToMany('App\Modelos\Cie10', 'cie10_teleconceptos');
    }

    public function integrantesGirs()
    {
        return $this->hasMany('App\Modelos\IntegrantesJuntaGirs', 'teleconcepto_id');
    }

    public function adjuntos()
    {
        return $this->hasMany('App\Modelos\AdjuntoTeleconcepto');
    }
}
