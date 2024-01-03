<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Acciones_mejoras extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['nombre','responsables','fecha_cumplimiento','fecha_seguimiento','estado', 'analisisevento_id', 'observacion'];

    protected $casts = [
        'responsables' => 'array',
    ];

    public function adjuntos()
    {
        return $this->hasMany('App\Modelos\AdjuntoEvento', 'accionmejora_id');
    }
}
