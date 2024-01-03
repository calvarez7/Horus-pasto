<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AnTemp extends Model
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
        'cie10',
        'birthDate',
        'gestationalAge',
        'birthHour',
        'prenatalManagement',
        'gender',
        'weight',
        'causeDeath',
        'deathDate',
        'deathHour',
        'paq_temps_id',
    ];
}
