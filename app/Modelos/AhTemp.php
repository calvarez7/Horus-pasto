<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AhTemp extends Model
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
        'entryDay',
        'entryDate',
        'entryHour',
        'authNumber',
        'causeExternal',
        'entryDiagnosis',
        'outputDiagnosis',
        'diagnosisRelationship1',
        'diagnosisRelationship2',
        'diagnosisRelationship3',
        'diagnosisComplication',
        'outputStatus',
        'diagnosisDeathCause',
        'outputDate',
        'outputHour',
        'paq_temps_id',
    ];
}
