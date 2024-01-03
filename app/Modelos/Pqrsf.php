<?php

namespace App\Modelos;

use App\Repositories\PqrsfRepository;
use Illuminate\Database\Eloquent\Model;

class Pqrsf extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Subcategoria_id', 'Paciente_id', 'Estado_id', 'Area_id', 'Empleado_id', 'Reabierta', 'Priorizacion', 'Atr_calidad',
        'Ips_afectada', 'Prestador', 'Medicamento', 'Procedente', 'CCgenera', 'Nombregenera', 'Email', 'Telefono', 'Tiposolicitud',
        'Canal', 'Descripcion', 'Pqr_codigo', 'Fecha_creacion', 'Afec_tipodoc', 'Afec_numdoc', 'Afec_nombres', 'Afec_direccion',
        'Afec_municipio', 'Afec_depto', 'Pqr_estado', 'derecho', 'deber'
    ];

    public function Gestion_pqrsfs()
    {
        return $this->hasMany('App\Modelos\Gestions_pqrsf', 'Pqrsf_id');
    }

    public function Subcategoriaspqrsf()
    {
        return $this->hasMany('App\Modelos\Subcategoriaspqrsf', 'Pqrsf_id');
    }

    public function Permisospqrsf()
    {
        return $this->hasMany('App\Modelos\Asignado', 'Pqrsf_id');
    }

    public static function getRepository(){
        return  new PqrsfRepository();
    }
}
