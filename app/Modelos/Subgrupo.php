<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Subgrupo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Grupo_id', 'Nombre', 'Descripcion', 'Estado',
    ];

    public function grupo()
    {
        return $this->belongsTo('App\Modelos\Grupo');
    }

    public function detallearticulo()
    {
        return $this->belongsToMany('App\Modelos\Detallearticulo');
    }
}
