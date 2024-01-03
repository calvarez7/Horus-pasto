<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\DetalleEsquemaRepository;

class Detallesquema extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'id','dosis','unidadMedida','indiceDosis','nivelOrdenamiento','via',
        'dosisFormulada','descripcionDosis','cantidadAplicaciones','diasAplicacion',
        'frecuencia','frecuenciaDuracion','observaciones','codesumisId','esquemaId'

    ];

    public function esquema()
    {
        return $this->belongsTo('App\Modelos\Esquema');
    }

    public static function getRepository()
    {
        return new DetalleEsquemaRepository();
    }
}
