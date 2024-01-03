<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Detallearticulo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    protected $casts = [
        'critico' => 'boolean',
    ];

    public function articulo()
    {
        return $this->belongsTo('App\Modelos\Articulo');
    }

    public function bodegarticulo()
    {
        return $this->hasMany('App\Modelos\Bodegarticulo');
    }

    public function codesumi()
    {
        return $this->belongsTo('App\Modelos\Codesumi');
    }

    public function catalogo()
    {
        return $this->hasMany('App\Modelos\Catalogo');
    }

    public function orden()
    {
        return $this->hasMany('App\Modelos\Orden');
    }
}
