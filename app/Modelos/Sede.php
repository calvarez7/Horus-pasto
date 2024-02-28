<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Nombre', 'Direccion', 'Telefono', 'Nit', 'Municipio_id','entidad_id','Sedeprestador_id'];

    public function consultorio()
    {
        return $this->hasMany('App\Modelos\Consultorio');
    }

    public function municipio()
    {
        return $this->belongsTo('App\Modelos\Municipio');
    }

}
