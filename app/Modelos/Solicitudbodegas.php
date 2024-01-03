<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Solicitudbodegas extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Cantidad', 'Codesumi_id', 'Bodegaorigen_id', 'Bodegadestino_id', 'Usuariosolicita', 'Usuariosolicita_id', 'Estado_id', 'Motivo', 'Tipotransacion_id',
    ];

    public function codesumis()
    {
        return $this->belongsTo('App\Modelos\Codesumi');
    }

    public function bodega()
    {
        return $this->belongsTo('App\Modelos\Bodega');
    }

    public function detalles()
    {
        return $this->hasMany('App\Modelos\DetalleSolicitudBodega', 'SolicitudBodega_id');
    }
}
