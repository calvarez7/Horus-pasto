<?php

namespace App\Services\Fias;

use App\Repositories\F2ARepository;
use App\Repositories\F2DRepository;
use App\Repositories\F3Repository;
use App\Repositories\F4Repository;
use App\Repositories\F5Repository;
use App\Repositories\F14Repository;
use App\Repositories\F15Repository;
use App\Repositories\F17Repository;
use App\Repositories\F18Repository;
use App\Repositories\F2BRepository;
use App\Repositories\F2CRepository;
use Exception;

class FiasService {

    /**
     * Determina el tipo de fias que se debe instanciar
     * @param string $fias
     * @return Instance
     * @author David Peláez
     */
    public function determinarFias(String $fias){

        switch ($fias) {
            case 'F2A':
                return (Object) [
                    'repository' => new F2ARepository,
                    'service' => new F2AService
                ];
                break;
            case 'F2B':
                return (Object) [
                    'repository' => new F2BRepository,
                    'service' => new F2BService
                ];
                break;
            case 'F2C':
                return (Object) [
                    'repository' => new F2CRepository,
                    'service' => new F2CService
                ];
                break;
            case 'F2D':
                return (Object) [
                    'repository' => new F2DRepository,
                    'service' => new F2DService
                ];
                break;
            case 'F3':
                return (Object) [
                    'repository' => new F3Repository,
                    'service' => new F3Service
                ];
                break;
            case 'F4':
                return (Object)[
                    'repository' => new F4Repository,
                    'service' => new F4Service
                ];
                break;
            case 'F5':
                return (Object)[
                    'repository' => new F5Repository,
                    'service' => new F5Service
                ];
                break;
            case 'F14':
                return (Object)[
                    'repository' => new F14Repository,
                    'service' => new F14Service
                ];
                break;
            case 'F15':
                return (Object)[
                    'repository' => new F15Repository,
                    'service' => new F15Service
                ];
                break;
            case 'F17':
                return (Object)[
                    'repository' => new F17Repository,
                    'service' => new F17Service,
                ];
                break;
            case 'F18':
                return (Object)[
                    'repository' => new F18Repository,
                    'service' => new F18Service,
                ];
                break;
            default:
                throw new Exception('El tipo de fias no es valido.');
                break;
        }

    }

}
