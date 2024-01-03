<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Registrocovi extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function seguimientosCovid()
    {
        return $this->hasMany('App\Modelos\Seguimientocovid');
    }

}
