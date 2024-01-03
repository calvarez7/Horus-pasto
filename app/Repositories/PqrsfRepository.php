<?php
namespace App\Repositories;

use App\Modelos\Pqrsf;
use Illuminate\Support\Facades\DB;

class PqrsfRepository
{
    public function checkStatus($request){ 

       return Pqrsf::select(['Estados.Nombre as Estado', 'Pqrsfs.id', 'Pqrsfs.Paciente_id as Paciente',
                'Pacientes.Primer_Nom as PrimerN', 'Pacientes.Primer_Ape as PrimerA', 'Pacientes.Num_Doc as NumDoc'])
                ->join('Estados', 'Pqrsfs.Estado_id', 'Estados.id')
                ->leftjoin('Pacientes', 'Pqrsfs.Paciente_id', 'Pacientes.id')
                ->with(['Gestion_pqrsfs' => function ($query) {
                    $query->select('gestions_pqrsf.id', 'gestions_pqrsf.Pqrsf_id', 'gestions_pqrsf.Tipo_id', 'gestions_pqrsf.Motivo',
                      'gestions_pqrsf.created_at')
                        ->where('Tipo_id', 9)
                        ->with(['Adjuntos_pqrsf' => function ($query) {
                            $query->select('Ruta', 'Gestion_id')
                                ->get();
                        }])
                        ->get();
                }])
                ->where('Pqrsfs.id', $request->radicado );
    }

}