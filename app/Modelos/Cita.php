<?php

namespace App\Modelos;

use App\Repositories\CitaRepository;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Cita extends Model implements Auditable
{

    use AuditableTrait;

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $fillable = ['Agenda_id', 'Hora_Inicio', 'Hora_Final', 'Cantidad', 'Estado_id', 'atendida'];

    public function agenda()
    {
        return $this->belongsTo('App\Modelos\Agenda', 'Agenda_id');
    }

    public function estados()
    {
        return $this->belongsToMany('App\Modelos\Estado')->withPivot('User_id', 'Estado_id', 'Cita_id', 'Actualizado_por')->withTimestamps();
    }

    public function paciente()
    {
        return $this->belongsToMany('App\Modelos\Paciente')->withPivot('Paciente_id', 'Cita_id', 'Usuario_id', 'Cup_id', 'Fecha_solicita', 'especialidad', 'Estado_id')->withTimestamps();
    }

    public function citaPaciente(){
        return $this->hasMany(citapaciente::class, 'Cita_id');
    }

    public static function getRepository(){
        return  new CitaRepository();
    }

    public function adjuntos_cita()
    {
        return $this->hasMany('App\Modelos\Adjuntos_general', 'cita_id');
    }
}
