<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class ActividadDesarrollo extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable =['estado_id', 'titulo', 'detalle', 'tiempo_inicio', 'tiempo_fin', 'created_by'];
    protected $table  = 'actividades_desarrollo';
    protected $casts = [
        'created_at' => 'date:Y-m-d'
    ];

    public function responsables()
    {
        return $this->belongsToMany('App\User','responsables_desarrollos', 'actividad_desarrollo_id', 'user_id');
    }
    public function archivos()
    {
        return $this->hasMany('App\Modelos\ArchivoDesarrollo', 'actividad_desarrollo_id');
    }
    public function comentarios()
    {
        return $this->hasMany('App\Modelos\ComentarioDesarrollo', 'actividad_desarrollo_id');
    }
    public function creador()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function estados()
     {
         return $this->belongsTo('App\Modelos\Estado', 'estado_id');
     }


}
