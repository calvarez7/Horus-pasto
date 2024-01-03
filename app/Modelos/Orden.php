<?php

namespace App\Modelos;

use App\Repositories\OrdenRepository;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Tiporden_id', 'Cita_id', 'Usuario_id', 'paginacion', 'Fechadispensacion', 'Estado_id', 'Fechaorden', 'scheme_name', 'page', 'total_pages', 'day', 'repetition_frequency', 'biography','FechaAgendamiento','estadoEnfermeria','observacionEnfermeria','ubicacion_id','observacion_ubicacion','fecha_pendiente','usuario_pendiente','firma_orden','autorizacion'];

    public function tiporden()
    {
        return $this->belongsTo('App\Modelos\Tiporden');
    }

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente', 'Cita_id');
    }

    public function tipordens()
    {
        return $this->belongsTo('App\Modelos\Tiporden', 'Tiporden_id');
    }

    public function cups()
    {
        return $this->hasMany('App\Modelos\Cup');
    }

    public function cupordens()
    {
        return $this->hasMany('App\Modelos\Cuporden', 'Orden_id');
    }

    public function detaarticulordens()
    {
        return $this->hasMany('App\Modelos\Detaarticulorden', 'Orden_id');
    }

    public function incapacidad()
    {
        return $this->belongsTo('App\Modelos\Incapacidade');
    }

    public function FormulaOptometria()
    {
        return $this->belongsTo('App\Modelos\FormulaOptometria');
    }

    public function cobro()
    {
        return $this->hasMany('App\Modelos\Cobro', 'orden_id');
    }

    public static function getRepository()
    {
        return new OrdenRepository();
    }
}
