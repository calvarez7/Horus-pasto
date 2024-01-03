<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Insumos_procedimiento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'producto', 'cantidad', 'Citapaciente_id','estado_id','usuario_id'
    ];

}
