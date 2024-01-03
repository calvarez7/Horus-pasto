<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Tipotransacion_id', 'Numfacturazeus', 'Numfactura', 'prestador_id', 'Orden_id', 'BodegaOrigen_id', 'BodegaDestino_id', 'Reps_id', 'Estado_id', 'usuario_id', 'created_at',
    ];

    public function bodegatransacion()
    {
        return $this->hasMany('App\Modelos\Bodegatransacion', 'Movimiento_id');
    }

    public function notastransacion()
    {
        return $this->hasMany('App\Modelos\Notastransacion');
    }

    public function rep()
    {
        return $this->belongsTo('App\Modelos\Rep');
    }
}
