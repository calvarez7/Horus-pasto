<?php
namespace App\Repositories;

use App\Modelos\citapaciente;

class CitaRepository
{
    public function cupspaciente($paciente){

        $cups = citapaciente::select(['cups.Nombre', 'cups.id', 'cupordens.Orden_id'])
        ->join('ordens', 'ordens.Cita_id', 'cita_paciente.id')
        ->join('cupordens', 'cupordens.Orden_id', 'ordens.id')
        ->join('cups', 'cupordens.Cup_id', 'cups.id')
        ->where('cita_paciente.Paciente_id', $paciente->id)
        ->whereIn('cupordens.Estado_id', [1,7])
        ->get();

        return $cups;
    }
}
