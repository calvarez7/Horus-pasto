<?php
namespace App\Repositories;

use App\Modelos\Sedeproveedore;
use Illuminate\Support\Facades\DB;

class SedeproveedoreRepository
{
    public function getSedePrestadorPqrsf(){ 

        return $ips = Sedeproveedore::select(['id', 'Nombre']);
        
    }

    public function getProveedores(){

        return $sedeproveedor = Sedeproveedore::select(['Sedeproveedores.*','Prestadores.nit'])
        ->join('Prestadores', 'Sedeproveedores.Prestador_id', 'Prestadores.id');

    }
}
