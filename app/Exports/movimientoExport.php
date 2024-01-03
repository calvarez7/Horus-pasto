<?php

namespace App\Exports;

use App\Modelos\Movimiento;
use Maatwebsite\Excel\Concerns\FromCollection;

class movimientoExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Movimiento::all();
    }
}
