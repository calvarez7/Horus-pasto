<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Megustanoticia extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
   protected $fillable = ['like','user_id','blog_id'];

   public function relacion(){
       return $this->belongsTo(blog::class);
   }
}
