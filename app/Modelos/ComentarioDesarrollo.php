<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComentarioDesarrollo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    

    use SoftDeletes;

    protected $guarded =[];
    protected $table = 'comentario_desarrollos';
    protected $with = ['user'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
