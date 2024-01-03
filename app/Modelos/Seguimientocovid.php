<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Seguimientocovid extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function registrocovi()
    {
        return $this->hasOne('App\Modelos\Registrocovi');
    }
}
