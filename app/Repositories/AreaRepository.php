<?php
namespace App\Repositories;

use App\Modelos\Area;
use Illuminate\Support\Facades\DB;

class AreaRepository
{
    public function all(){ 

        return $area = Area::select(['id', 'Nombre']);
        
    }
}
