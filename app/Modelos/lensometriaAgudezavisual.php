<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class lensometriaAgudezavisual extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
            'lateralidad_od',
            'esf_od',
            'cyl_od',
            'eje_od',
            'add_od',
            'lateralidad_oi',
            'esf_oi',
            'cyl_oi',
            'eje_oi',
            'add_oi',
            'checkboxSC',
            'checkboxCC',
            'agudeza_od',
            'agudezavp_od',
            'agudeza_oi',
            'agudezavp_oi',
            'ocular_vl',
            'ocular_vp',
            'ocular_ppc',
            'citapaciente_id',
    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
