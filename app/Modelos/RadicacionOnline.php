<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class RadicacionOnline extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function adjuntoradicado()
    {
        return $this->hasMany('App\Modelos\Adjuntos_radicaciononline', 'radicaciononline_id');
    }

    public function gestion()
    {
        return $this->hasMany('App\Modelos\Gestion_radicaciononline', 'radicaciononline_id');
    }
}
