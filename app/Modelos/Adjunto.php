<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Gestion_id', 'Ruta', 'Nombre'
    ];

    public function pqrsf()
    {
        return $this->hasMany('App\Modelos\Pqrsf');
    }
}
