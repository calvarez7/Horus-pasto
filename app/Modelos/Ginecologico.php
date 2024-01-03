<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Ginecologico extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Citapaciente_id', 'Fechaultimamenstruacion', 'Gestaciones', 'Partos', 'Abortoprovocado',
        'Abortoespontaneo', 'Mortinato', 'Gestantefechaparto', 'Gestantenumeroctrlprenatal', 'Eutoxico',
        'Cesaria', 'Cancercuellouterino', 'Ultimacitologia', 'Resultado', 'Menarquia', 'Ciclos', 'Regulares',
        'Observaciongineco',"checkboxFechaultimamenstruacion","checkboxGestaciones","checkboxPartos",
        "checkboxAbortoprovocado","checkboxAbortoespontaneo","checkboxMortinato","checkboxGestante",
        "checkboxEutoxico","checkboxCesaria","checkboxCancercuellouterino","checkboxCitologia",
        "checkboxMenarquia","checkboxCiclos","checkboxRegulares",
    ];

    public function citapaciente()
    {
        return $this->hasMany('App\Modelos\citapaciente');
    }
}
