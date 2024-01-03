<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AfTemp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = [
        'id',
        'code',
        'documentType',
        'documentNumber',
        'billNumber',
        'billDate',
        'startDate',
        'finishDate',
        'entityCode',
        'adminName',
        'agreementNumber',
        'benefitsPlan',
        'policyNumber',
        'copayValue',
        'commissionValue',
        'discountValue',
        'netoValue',
        'paq_temps_id',
    ];
}
