<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Incapacidade extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Fechainicio', 'Dias', 'Fechafinal', 'Prorroga', 'Adjuntoincapacidad',
        'Orden_id', 'Cie10_id', 'Usuarioordena_id', 'Usuarioedit_id', 'Estado_id', 'Descripcion',
        'Contingencia', 'Laboraen',
    ];

    public function orden()
    {
        return $this->belongsTo('App\Modelos\Orden');
    }
}
