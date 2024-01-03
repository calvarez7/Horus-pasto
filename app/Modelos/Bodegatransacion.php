<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Bodegatransacion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Lote_id', 'Movimiento_id', 'Cantidadtotal', 'CantidadtotalFactura', 'concentracion_dispensada', 'Cantidadtotalinventario', 'Precio',
        'Valor', 'Valortotal', 'Valorpromedio', 'Estado_id', 'Bodegarticulo_id', 'Solicitud_id', 'created_at', 'Motivo',
    ];

    public function bodegarticulo()
    {
        return $this->belongsTo('App\Modelos\Bodegarticulo');
    }

    public function movimeintos()
    {
        return $this->belongsTo('App\Modelos\Movimiento');
    }

    public function lote()
    {
        return $this->belongsTo('App\Modelos\Lote');
    }

}
