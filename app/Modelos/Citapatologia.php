<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Citapatologias extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Cie10_id', 'Cita_id', 'Estado_id',
    ];

    public function cie10()
    {
        return $this->belongsTo('App\Modelos\Cie10');
    }

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
