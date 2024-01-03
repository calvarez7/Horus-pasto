<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'fecha','ambito','finalidad','cup_id','user_id','paciente_id','tema'
    ];
}
