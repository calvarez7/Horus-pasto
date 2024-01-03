<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Us extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $guarded = [];

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'Municipio_id');
    }

}
