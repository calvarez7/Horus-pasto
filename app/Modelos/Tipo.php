<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Estado_id', 'Tipo_id'
    ];

    public function tipotransacion()
    {
        return $this->hasMany('App\Modelos\Tipotransacion');
    }
}
