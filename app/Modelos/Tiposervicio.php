<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tiposervicio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['Nombre', 'Estado_id'];

    public function cupservicios()
    {
        return $this->hasMany('App\Modelos\Cupservicio');
    }
}
