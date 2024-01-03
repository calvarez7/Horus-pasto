<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipoprestador extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $Fillable = [
        'Nombre', 'Estado_id',
    ];

    public function prestadores()
    {
        return $this->hasMany('App\Modelos\Prestadore');
    }
}
