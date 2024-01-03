<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Categoriaproducto extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombre', 'descripcion', 'estado_id',
    ];

    public function productos()
    {
        return $this->hasMany('App\Modelos\Producto');
    }
}
