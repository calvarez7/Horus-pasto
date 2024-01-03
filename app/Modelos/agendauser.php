<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class agendauser extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Medico_id', 'Agenda_id',
    ];

    public function agenda()
    {

        return $this->belongsTo('App\Modelos\Agenda');
    }
}
