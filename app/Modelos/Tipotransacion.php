<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipotransacion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Transacion_id', 'Tipo_id',
    ];

    public function tipo()
    {
        return $this->belongsTo('App\Modelos\Tipo');
    }
}
