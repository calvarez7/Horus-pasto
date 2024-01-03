<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    //
    protected $fillable = ['message', 'Archivo', 'User_id', 'Bitacora_id', 'Estado_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'User_id');
    }

    public function referencia()
    {
        return $this->belongsTo('App\Modelos\Bitacora', 'Bitacora_id');
    }
}
