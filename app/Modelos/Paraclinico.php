<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Paraclinico extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['resultadoCreatinina','ultimaCreatinina','resultaGlicosidada','fechaGlicosidada',
 'resultadoAlbuminuria','fechaAlbuminuria','resultadoColesterol','fechaColesterol','resultadoHdl',
'fechaHdl','resultadoLdl','fechaLdl','resultadoTrigliceridos','fechaTrigliceridos','resultadoGlicemia',
'fechaGlicemia','resultadoPht','fechaPht','resultadoHemoglobina','albumina','fechaAlbumina',
'fosforo','fechaFosforo','resultadoEkg','fechaEkg','glomerular','fechaGlomerular','usuario_id',
'paciente_id','Citapaciente_id','checkboxParaclinicos','nombreParaclinico','fechaParaclinico',
'resultadoParaclinico'];
}
