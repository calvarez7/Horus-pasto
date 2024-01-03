<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Areahelp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Descripcion', 'Permission_id', 'Estado_id',
    ];

    protected $table = 'areas_help';
}
