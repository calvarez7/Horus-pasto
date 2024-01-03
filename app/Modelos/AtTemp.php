<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AtTemp extends Model
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
        'serviceCode',
        'authNumber',
        'serviceType',
        'serviceName',
        'total',
        'unitValue',
        'totalValue',
        'paq_temps_id',
    ];
}
