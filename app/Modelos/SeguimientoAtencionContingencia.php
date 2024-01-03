<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class SeguimientoAtencionContingencia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'seguimiento_atencion_contingencia';

    protected $guarded = [];

}
