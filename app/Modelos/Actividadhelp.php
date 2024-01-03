<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Actividadhelp extends Model
{

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Descripcion', 'Categoria_id',
    ];

    protected $table = 'actividades_help';
}
