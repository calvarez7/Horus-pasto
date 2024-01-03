<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class actividadcargo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Cargo_id', 'Nombre', 'Estado_id',
    ];

    public function cargo()
    {
        return $this->belongsTo('App\Modelos\Cargo');
    }

    public function actividaduser()
    {
        return $this->hasMany('App\Modelos\Actividaduser');
    }
}
