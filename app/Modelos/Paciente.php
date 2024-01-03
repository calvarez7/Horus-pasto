<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\PacienteRepository;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Paciente extends Model implements Auditable
{
    use AuditableTrait;
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $casts = [
        'Dpto_Atencion' => 'integer',
        'Estado_Afiliado' => 'integer',
        'IPS' => 'integer',
        'Tienetutela' => 'boolean'
    ];

    protected $guarded = [ ];

    public function estados()
    {
        return $this->belongsToMany('App\Modelos\Estado')->withPivot('Paciente_id', 'Estado_id', 'Actualizado_por')->withTimestamps();
    }

    public function citas()
    {
        return $this->hasMany('App\Modelos\Cita');
    }

    public function citaPacientes()
    {
        return $this->hasMany('App\Modelos\citapaciente');
    }

    public function pqrsf()
    {
        return $this->hasMany('App\Modelos\Pqrsf');
    }

    public function antecedente()
    {
        return $this->hasMany('App\Modelos\Antecedente');
    }

    public function marcas()
    {
        return $this->hasMany('App\Modelos\Marca');
    }

    public function colegios()
    {
        return $this->hasMany('App\Modelos\Colegio');
    }

    public static function getRepository(){
        return  new PacienteRepository();
    }
}
