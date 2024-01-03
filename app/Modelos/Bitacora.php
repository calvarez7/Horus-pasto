<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = ['User_id', 'Referencia_id', 'Estado'];

    public function messages()
    {
        return $this->hasMany('App\Modelos\Message', 'Bitacora_id');
    }

    public function referencia()
    {
        return $this->belongsTo('App\Modelos\Referencia', 'Referencia_id');
    }
}
