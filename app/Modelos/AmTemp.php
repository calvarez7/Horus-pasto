<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AmTemp extends Model
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
        'authNumber',
        'cum',
        'medicamentType',
        'numberUnits',
        'unitValue',
        'totalValue',
        'paq_temps_id'
    ];
}
