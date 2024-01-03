<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class actividad_internacional extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'actividad_internacionals';
    protected $guarded = [];
}
