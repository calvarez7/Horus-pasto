<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class declaracion_fondos extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'declaracion_fondos';
    protected $guarded = [];
}
