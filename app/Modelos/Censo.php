<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Censo extends Model
{
    use HasFactory;

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'ips',
        'atencion_admision',
        'tipo_identficicacion',
        'numero_identificacion',
        'codigo',
        'diganostico_cie10',
        'fecha_ingreso',
        'nombres_apellidos',
        'ubicacion',
        'especialidad',
        'dias_estancia',
        'via_ingreso',
        'Asegurador',
        'grupo_diagnostico',
        'ips_basica',
        'Alta',
        'areas_reporte',
        'pym',
        'concurrencia_id'
    ];
}
