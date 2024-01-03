<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class EspecialidadesCup extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'especialidad_id', 'cup_id', 'estado_id'
    ];
}
