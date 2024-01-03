<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ApTemp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = [
        'id',
        'billNumber',
        'documentType',
        'documentNumber',
        'processDate',
        'authNumber',
        'processCode',
        'processScope',
        'processFinaly',
        'personalServes',
        'principalDiagnosis',
        'diagnosisRelationship',
        'complication',
        'qxAct',
        'processValue',
        'paq_temps_id'
    ];
}
