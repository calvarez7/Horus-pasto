<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Historiatarifa extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Tarifario_id', 'Tarifa', 'Valor',
    ];

    public function tarifario()
    {
        return $this->belongsTo('App\Modelos\Tarifario');
    }
}
