<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetallesResolucion1552 extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];
    protected $table = 'detalles_resolucion';
}
