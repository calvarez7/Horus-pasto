<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class PaqueteServicio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];
    protected $with = ["detallePaquete"];
    protected $casts = [
        'estado_id' => 'integer',
    ];


    public function detallePaquete()
    {
        return $this->hasMany('App\Modelos\DetallePaqueteServicio', 'paquete_servicio_id','id');
    }

}
