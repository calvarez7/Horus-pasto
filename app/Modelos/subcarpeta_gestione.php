<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class subcarpeta_gestione extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'subcarpetas';
    protected $fillable = [
        'nombre',
        'ruta',
        'carpeta_id',
        'subcarpeta_id',
        'user_id'
    ];

    public function subcarpeta()
    {
        return $this->hasMany('App\Modelos\subcarpeta_gestione','subcarpeta_id');
    }

    public function relacion()
    {
        return $this->hasMany(subcarpeta_gestione::class,'subcarpeta_id')->with('subcarpeta');
    }
    public function datos()
    {
        return $this->hasMany(documentale::class,'carpeta_id');
    }
}
