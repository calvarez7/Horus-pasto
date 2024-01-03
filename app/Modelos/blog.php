<?php

namespace App\Modelos;

use App\User;
use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'blogs';
    protected $fillable = ['imagen','subtexto','fecha_inicio','titulo','texto','estado_id'];

    public function relacion(){
        return $this->hasMany(Megustanoticia::class,'blog_id','id');
    }
    public function relacions(){
        return $this->hasMany(Megustanoticia::class,'blog_id','id');
    }
    public function userlike(){
        return $this->hasMany(Megustanoticia::class,'blog_id','id');
    }
    public function detalle(){
        return $this->hasMany(conteonoticia::class,'noticia_id','id');
    }
}
