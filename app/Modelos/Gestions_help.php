<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Gestions_help extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'User_id', 'Helpdesk_id', 'Tipo_id', 'Motivo', 'responsable'
    ];

    protected $table = 'gestions_help';

    public function Adjuntos_helpdesks()
    {
        return $this->hasMany('App\Modelos\Adjuntoshelp', 'Gestion_id');
    }
}
