<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class IntegrantesJuntaGirs extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'integrantes_junta_girs';

    protected $guarded = [];

    public function teleconcepto()
    {
        return $this->belongsTo('App\Modelos\Teleconcepto');
    }
}
