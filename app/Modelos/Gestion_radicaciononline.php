<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Gestion_radicaciononline extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function adjuntosgestion()
    {
        return $this->hasMany('App\Modelos\Adjuntos_radicaciononline', 'gestionradicaciononline_id');
    }
}
