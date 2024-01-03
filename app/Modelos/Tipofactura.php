<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Tipofactura extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre', 'Descripcion', 'Estado_id',
    ];

    public function facturas()
    {
        return $this->hasMany('App\Modelos\Factura');
    }
}
