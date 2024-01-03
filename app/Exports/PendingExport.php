<?php

namespace App\Exports;

use App\Modelos\Orden;
use Maatwebsite\Excel\Concerns\FromCollection;

class PendingExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $startDate;
    private $finishDate;
    private $bodega;

    public function __construct($startDate, $finishDate, $bodega)
    {
        $this->startDate  = $startDate;
        $this->finishDate = $finishDate;
        $this->bodega     = $bodega;
    }

    public function collection()
    {
        return Orden::select(['p.Tipo_Doc',
            'p.Num_Doc',
            'p.Primer_Nom',
            'p.SegundoNom',
            'p.Primer_Ape',
            'p.Segundo_Ape',
            'ordens.id',
            'cs.Nombre',
            'dao.Cantidadmensualdisponible',
            'ordens.created_at',
            'm.BodegaOrigen_id'])
            ->join('movimientos as m', 'm.Orden_id', 'ordens.id')
            ->join('bodegatransacions as bt', 'bt.Movimiento_id', 'm.id')
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('detaarticulordens as dao', 'dao.Orden_id', 'ordens.id')
            ->join('codesumis as cs', 'dao.codesumi_id', 'cs.id')
            ->whereIn('ordens.Estado_id', [18, 19])
            ->whereIn('dao.Estado_id', [18, 19])
            ->where('m.BodegaOrigen_id', $this->bodega)
            ->whereBetween('ordens.created_at', [$this->startDate, $this->finishDate])
            ->orderBy('ordens.created_at', 'asc')
            ->get();
    }
}
