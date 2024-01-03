<?php

namespace App\Modelos;

use App\Repositories\EsquemaRepository;
use Illuminate\Database\Eloquent\Model;

class Esquema extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'nombreEsquema', 'abrevEsquema', 'ciclos', 'frecuenciaRepeat',
        'biografia', 'estadoId',
    ];

    public function detallesquemas()
    {
        return $this->hasMany('App\Modelos\Detallesquema', 'esquemaId');
    }
    public function estados(){

        return $this->belongsTo('App\Modelos\Estado','id','estadoId');
    }

    public static function getRepository()
    {
        return new EsquemaRepository();
    }
}
