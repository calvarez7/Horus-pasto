<?php

namespace App\Modelos;

use App\Modelos\Asignado_eventos;
use Illuminate\Database\Eloquent\Model;

class Eventos_adverso extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['cita_paciente_id', 'bodegaimagenologia_id', 'user_id', 'fecha_venci_medicamento', 'lote_medicamento',
        'invima_medicamento', 'sede_ocurrencia', 'sede_reportante', 'clasificacion', 'fecha_ocurrencia',
        'relacion', 'dispositivo', 'referencia', 'modelo', 'serial', 'invima_dispositivo', 'descripcion_hechos',
        'accion_tomada', 'evento_id', 'detallearticulo_id', 'tipo_id', 'estado_id', 'paciente_id', 'clasificacionevento_id',
        'tipoevento_id', 'clasificacion_invima', 'dosis_medicamento', 'medida_medicamento', 'via_medicamento', 'frecuencia_medicamento',
        'infusion_medicamento', 'nombre_equipo', 'marca_equipo', 'modelo_equipo', 'serie_equipo', 'placa_equipo', 'red',
        'servicio_ocurrencia', 'servicio_reportante', 'otro_evento', 'lote_dispositivo', 'priorizacion', 'probabilidad',
        'impacto', 'previsibilidad', 'nivel_priorizacion', 'severidad_evento', 'voluntariedad', 'profesional','identificacion_riesgo'
    ];

    protected $with = ['adjuntos'];

    public function pre_analisis()
    {
        return $this->hasMany('App\Modelos\Analisis_evento', 'eventosadverso_id');
    }

    public function asignado_evento()
    {
        return $this->hasMany(Asignado_eventos::class, 'eventosadverso_id');
    }

    public function adjuntos()
    {
        return $this->hasMany('App\Modelos\AdjuntoEvento', 'eventoadverso_id');
    }
}
