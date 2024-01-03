<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tiporden extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['Nombre', 'Descripcion'];

    public function citapaciente()
    {
        return $this->hasMany('App\Modelos\citapaciente');
    }
}
