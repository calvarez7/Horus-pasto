<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class video extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $table = 'videos';
    protected $fillable = ['nombre','estado_id','link','fecha_inicio','fecha_final'];

    public function relacion(){
        return $this->hasMany(contadorvideo::class);
    }


}
