<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ([
        'Numlote', 'Fvence', 'Cantidadisponible', 'Bodegarticulo_id', 'aprovechamiento', 'Estado_id',
    ]);

    public function bodegarticulo()
    {
        return $this->belongsTo('App\Modelos\Bodegarticulo');
    }

    public function bodedatransacions()
    {
        return $this->hasMany('App\Modelos\Bodegatransacion');
    }

    public function conteinventario()
    {
        return $this->hasMany('App\Modelos\Conteoinventario');
    }
}
