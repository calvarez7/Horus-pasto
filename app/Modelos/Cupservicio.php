<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Cupservicio extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Cup_id', 'Tiposervicio_id',
    ];

    public function tiposervicio()
    {
        return $this->belongsTo('App\Modelos\Tiposervicio');
    }

    public function cup()
    {
        return $this->belongsTo('App\Modelos\Cup');
    }

    public function tarifas()
    {
        return $this->hasMany('App\Modelos\Tarifa');
    }
}
