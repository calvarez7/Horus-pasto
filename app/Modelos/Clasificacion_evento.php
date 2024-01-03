<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Clasificacion_evento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['nombre','evento_id'];
}
