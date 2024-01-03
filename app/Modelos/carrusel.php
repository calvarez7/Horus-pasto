<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class carrusel extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'carousels';
    protected $fillable = [
        'imagen', 'estado_id','nombre','fecha_inicio','fecha_final','enlace'
    ];
}
