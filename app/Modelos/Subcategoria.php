<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['categoria_id','Nombre','Descripcion', 'Estado_id'];

    public function categorias()
    {
        return $this->hasMany('App\Modelos\Categorias');
    }

    public function pqrsf()
    {
        return $this->belongsTo('App\Modelos\Pqrsf');
    }

}
