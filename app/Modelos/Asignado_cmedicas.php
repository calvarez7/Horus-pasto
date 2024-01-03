<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Asignado_cmedicas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['permission_id','af_id', 'estado_id'];
}
