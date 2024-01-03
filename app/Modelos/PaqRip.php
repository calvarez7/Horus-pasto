<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PaqRip extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];
    protected $with = ["estado"];


    public function cts()
    {
        return $this->hasMany('App\Modelos\Ct','Id_Paquete');
    }

    public function afs()
    {
        return $this->hasMany('App\Modelos\Af','paq_id');
    }

    public function estado(){
        return  $this->belongsTo(Estado::class, 'estado_id');
    }
}
