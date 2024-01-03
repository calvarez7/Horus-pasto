<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class UsTemp extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = [
        'id',
        'documentType',
        'documentNumber',
        'adminCode',
        'userType',
        'firstName',
        'secondName',
        'firstSurname',
        'secondSurname',
        'age',
        'unit',
        'gender',
        'codeDaneDepartment',
        'codeDaneTown',
        'area',
        'paq_temps_id',
    ];
}
