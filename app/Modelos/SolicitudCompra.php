<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class SolicitudCompra extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    public function ordenCompra()
    {
        return $this->hasMany('App\Modelos\Ordencompra', 'solicitudcompra_id');
    }
}
