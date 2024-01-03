<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Resolucion1552 extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'resolucion_1552';
    protected $guarded = [];

}
