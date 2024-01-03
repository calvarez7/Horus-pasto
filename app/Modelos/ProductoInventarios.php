<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ProductoInventarios extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo('App\Modelos\Producto');
    }
}
