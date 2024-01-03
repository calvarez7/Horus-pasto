<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class CodesumiViadministracion extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $guarded = [];

    public function codesumi()
    {
        return $this->belongsTo('App\Modelos\Codesumi');
    }
}
