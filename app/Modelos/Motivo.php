<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Citapaciente_id', 'Motivo', 'Usuariosuspende_id',
    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
