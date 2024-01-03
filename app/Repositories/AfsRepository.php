<?php
namespace App\Repositories;

use App\Modelos\Af;
use Illuminate\Support\Facades\DB;

class AfsRepository
{
    public function getAllValuesInvoices(){

        $result = Af::select([
            DB::raw("SUM(af.valor_Neto) as totalValor"),
            DB::raw("COUNT(af.IF) as totalFacturas"),
        ]);
    }
}
