<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Descripcion', 'Tabla', 'Usuariomodifica_id', 'Model_id', 'Motivo',
    ];
}
