<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Codigomanual extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Tipomanual_id', 'Codigo', 'Descripcion', 'Valor', 'Cup_id', 'Estado_id', 'anio'
    ];

    public function valormanual()
    {
        return $this->hasMany('App\Modelos\Valormanual');
    }

    public function cup()
    {
        return $this->belongsTo('App\Modelos\Cup');
    }

    public function tipomanual()
    {
        return $this->belongsTo('App\Modelos\Tipomanuale');
    }
}
