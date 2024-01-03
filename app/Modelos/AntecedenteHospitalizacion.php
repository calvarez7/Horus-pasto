<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AntecedenteHospitalizacion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [ ];
}


