<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class adjunto_revision extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'adjunto_revisions';
    protected $guarded = [];

    public function revision(){
         return $this->hasMany('App\Modelos\revision_sarlaft','id');
    }
}
