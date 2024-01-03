<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetalleAtencionContingencia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    protected $table = 'detalle_atencion_contingencia';
}
