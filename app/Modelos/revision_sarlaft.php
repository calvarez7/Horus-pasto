<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class revision_sarlaft extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $table = 'revision_sarlafts';
    protected $guarded = [];

    public function adjuntos(){
        return $this->hasMany('App\Modelos\adjunto_revision', 'revision_sarlafts_id');
    }

}
