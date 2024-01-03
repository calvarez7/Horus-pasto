<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class DetallePaqueteServicio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];
    protected $with = ["paqueteServicio","cup"];


    public function paqueteServicio()
    {
        return $this->belongsTo('App\Modelos\PaqueteServicio','detalle_paquete_servicios_paquete_servicio_id_foreign','paquete_servicio_id');
    }


    public function cup()
    {
        return $this->hasOne('App\Modelos\Cup', 'id', 'cup_id');
    }
}
