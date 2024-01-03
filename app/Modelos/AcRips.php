<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class AcRips extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'citapaciente_id', 'paciente_id', 'cod_habilitacion_sede', 'tipo_doc', 'numero_doc', 'fecha_consulta',
        'orden_id', 'cod_cup', 'finalidad', 'causa_externa', 'dx_principal', 'dx_relacionado1',
        'dx_relacionado2', 'dx_relacionado3', 'tipo_diagnostico', 'valor_consulta', 'valor_cuota_moderada',
        'valor_neto_pagar', 'entidad_id', 'estado_id'
    ];
}
