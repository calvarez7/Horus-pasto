<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Sedes_sumimedical extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre', 'estado_id', 'Sedeprestador_id'
    ];

    protected $table = 'sedes_sumimedical';
}
