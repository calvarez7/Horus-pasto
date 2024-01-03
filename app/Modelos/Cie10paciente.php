<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cie10paciente extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }

    public function cie10()
    {
        return $this->belongsTo('App\Modelos\Cie10');
    }
}
