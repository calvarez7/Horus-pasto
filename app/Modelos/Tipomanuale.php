<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipomanuale extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Nombre', 'Descripcion', 'Estado_id',
    ];

    public function codigomanual()
    {
        return $this->hasMany('App\Modelos\Codigomanual');
    }
}
