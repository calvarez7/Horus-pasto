<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Agenda extends Model implements Auditable
{
    use AuditableTrait;

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'Fecha', 'Hora_Inicio', 'Consultorio_id', 'Hora_Final', 'Estado_id', 'Medico_id', 'Especialidad_id', 'Usuariocrea_id',
    ]; //no se puede maxivo

    public function estados()
    {
        return $this->belongsToMany('App\Modelos\Estado');
    }

    public function consultorio()
    {
        return $this->belongsTo('App\Modelos\Consultorio', 'Consultorio_id');
    }
    public function citas()
    {
        return $this->hasMany('App\Modelos\Cita', 'Agenda_id');
    }
    public function agendauser()
    {
        return $this->hasMany('App\Modelos\agendauser');
    }
    public function medico()
    {

        return $this->belongsTo('App\User', 'Medico_id');
    }

    public function especialidadtipoagendas()
    {
        return $this->belongsTo('App\Modelos\Especialidadtipoagenda');
    }

    /*public function tipoagenda()
{
return $this->belongsTo('App\Modelos\Tipoagenda');
}

public function usuario()
{
return $this->belongsTo('App\User');
}*/
}
