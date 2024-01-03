<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Nombre', 'Descripcion', 'Cantidad'];

    public function sede()
    {
        return $this->belongsTo('App\Modelos\Sede', 'Sede_id');
    }

    public function agenda()
    {
        return $this->hasMany('App\Modelos\Consultorio');
    }
}
