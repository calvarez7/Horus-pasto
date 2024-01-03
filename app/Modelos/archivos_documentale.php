<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class archivos_documentale extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre',
        'ruta',
        'subcarpeta_id',
        'user_id',
        'estado',
        'carpeta_id'
    ];
}
