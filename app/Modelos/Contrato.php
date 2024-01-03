<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Sedeproveedor_id', 'Tarifa', 'Manual', 'Ambito', 'Anio', 'Estado_id', 'entidad_id',
    ];

    public function tarifarios()
    {
        return $this->hasMany('App\Modelos\Tarifario');
    }

    public function familias()
    {
        return $this->belongsToMany('App\Modelos\Familia', 'contratofamilias');
    }

    public function sedeproveedor()
    {
        return $this->belongsTo('App\Modelos\Sedeproveedore');
    }
}
