<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipofamilia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Descripcion', 'Estado_id',
    ];

    public function familias()
    {
        return $this->hasMany('App\Modelos\Familia');
    }
}
