<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Categoriahelp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Descripcion', 'Area_id','alerta'
    ];

    protected $table = 'categorias_help';
}
