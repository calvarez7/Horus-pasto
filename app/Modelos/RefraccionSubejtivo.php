<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class RefraccionSubejtivo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
            'queratometria_od',
            'queratometria_oi',
            'refraccion_od',
            'refraccionAv_od',
            'refraccion_oi',
            'refraccionAv_oi',
            'subjetivo_od',
            'subjetivoAv_od',
            'subjetivo_oi',
            'subjetivoAv_oi',
            'citapaciente_id',
];

public function citapaciente()
{
    return $this->belongsTo('App\Modelos\citapaciente');
}
}
