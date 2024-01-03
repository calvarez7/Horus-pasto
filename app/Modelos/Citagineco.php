<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class citaginecos extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Cita_id', 'Ginecologia_id'];

    public function ginacologico()
    {
        return $this->belongsTo('App\Modelos\Ginecologico');
    }

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
