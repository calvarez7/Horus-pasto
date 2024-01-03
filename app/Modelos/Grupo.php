<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Nombre', 'Codigo', 'Estado_id'];

    public function subgrupos()
    {
        return $this->belongsToMany('App\Modelos\Subgrupo');
    }
}
