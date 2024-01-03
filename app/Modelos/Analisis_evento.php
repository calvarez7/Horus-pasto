<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Analisis_evento extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'eventosadverso_id', 'fecha_analisis', 'cronologia', 'consecuencias', 'acciones_inseguras', 'clasificacion',
        'paciente', 'tarea_tecnologia', 'individuo', 'equipo_trabajo', 'ambiente', 'organizacion', 'contexto',
        'accion_mejora', 'responsable', 'fecha_inicio', 'fecha_finalizacion', 'porcentaje_mejora', 'fecha_seguimiento',
        'porcentaje_cumplimiento', 'elemento_funcion', 'modo_fallo', 'efecto', 'npr', 'acciones_propuestas',
        'usercerro_id', 'usercreo_id', 'metodologia', 'que_fallo', 'porque_fallo', 'que_causo', 'accion_implemento',
        'despues_adminmedicamento', 'factores_explicarelevento', 'desaparecio_suspendermedicamento',
        'reaccion_medicamentosospechoso', 'ampliar_informacionpaciente', 'evaluacion_causalidad',
        'clasificacion_invima', 'seriedad', 'fecha_muerte', 'desenlace_evento', 'causas_esavi',
        'asociacion_esavi', 'ventana_mayoriesgo', 'evidencia_asociacioncausal', 'factores_esavi',
        'farmaco_cinetica', 'condiciones_farmacocinetica', 'prescribio_manerainadecuada',
        'medicamento_entrenamiento', 'potenciales_interacciones', 'notificacion_refieremedicamento',
        'problema_biofarmaceutico', 'deficiencias_sistemas', 'factores_asociados', 'medicamento_manerainadecuada',
        'descripcion_consecuencias','accion_resarcimiento' ,'requiere_reporte_ente', 'requiere_marca_especifica'
    ];

    public function accion_inseguras()
    {
        return $this->hasMany('App\Modelos\Acciones_inseguras', 'analisisevento_id');
    }

    public function accion_mejoras()
    {
        return $this->hasMany('App\Modelos\Acciones_mejoras', 'analisisevento_id');
    }

}
