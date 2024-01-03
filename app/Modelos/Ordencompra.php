<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Ordencompra extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    public function bodegarticulo()
    {
        return $this->belongsTo('App\Modelos\bodegarticulo');
    }

    public function solicitud()
    {
        return $this->belongsTo('App\Modelos\SolicitudCompra');
    }
}
