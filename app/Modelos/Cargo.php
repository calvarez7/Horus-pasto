<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Descripcion', 'Estado_id',
    ];

    public function actividadcargo()
    {
        return $this->hasMany('App\Modelos\Actividadcargo');
    }
}
