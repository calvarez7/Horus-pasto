<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Adjuntoshelp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Gestion_id', 'Ruta', 'Nombre',
    ];

    protected $table = 'adjuntos_help';

    public function helpdesk()
    {
        return $this->hasMany('App\Modelos\Helpdesk');
    }
}
