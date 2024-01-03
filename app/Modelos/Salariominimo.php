<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Salariominimo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'anio', 'valor'
    ];
}
