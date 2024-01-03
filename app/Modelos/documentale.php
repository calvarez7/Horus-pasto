<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class documentale extends Model
{

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['nombre', 'ruta', 'usuario_id'];

    // public function subcarpetas()
    // {
    //     return $this->hasMany(subcarpeta_gestione::class, 'carpeta_id');
    // }

    public function verRelaciones()
    {
        return $this->hasMany('App\Modelos\subcarpeta_gestione','carpeta_id');
    }
}
