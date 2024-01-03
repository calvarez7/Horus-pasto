<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AuTemp extends Model
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
        'outputDiagnosis',
        'entryDate',
        'entryHour',
        'authNumber',
        'externalCause',
        'diagnosisRelationshipOutput1',
        'diagnosisRelationshipOutput2',
        'diagnosisRelationshipOutput3',
        'userDestination',
        'outputStatus',
        'basicCauseDeath',
        'outputDate',
        'outputHour',
        'paq_temps_id'
    ];
}
