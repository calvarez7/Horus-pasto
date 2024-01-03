<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Estado extends Model implements Auditable
{
        use AuditableTrait;
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['Nombre', 'Tipo', 'Descripcion'];

    public function agendas()
    {
        return $this->belongsToMany('App\Modelos\Agenda');
    }

    public function pacientes()
    {
        return $this->belongsToMany('App\Modelos\Paciente');
    }

    public function citas()
    {
        return $this->belongsToMany('App\Modelos\Cita');
    }

    public function users()
    {
        return $this->belongsToMany('App\Modelos\User');
    }
}
