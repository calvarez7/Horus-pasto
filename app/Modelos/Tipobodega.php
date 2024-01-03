<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipobodega extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Nombre', 'Estado',
    ];

    public function bodega()
    {
        return $this->hasMany('App\Modelos\Bodega');
    }
}
