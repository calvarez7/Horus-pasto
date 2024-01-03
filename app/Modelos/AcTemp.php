<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AcTemp extends Model
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
        'consultationDate',
        'authNumber',
        'consultationCode',
        'Finalidad_Consulta',
        'causeExternal',
        'principalDiagnosis',
        'codeRelationship1',
        'codeRelationship2',
        'codeRelationship3',
        'principalDiagnosisType',
        'consultationValue',
        'feeValue',
        'netoValue',
        'paq_temps_id',
    ];
}
