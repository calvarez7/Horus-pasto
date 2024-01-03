<?php 
namespace App\Services\Rips;

use App\Repositories\ACCovidRepository;
use App\Repositories\ACRepository;
use App\Repositories\AFCovidRepository;
use App\Repositories\AFRepository;
use App\Repositories\AMCovidRepository;
use App\Repositories\AMRepository;
use App\Repositories\APCovidRepository;
use App\Repositories\APRepository;
use App\Repositories\ATCovidRepository;
use App\Repositories\ATRepository;
use App\Repositories\CTCovidRepository;
use App\Repositories\CTRepository;
use App\Repositories\USCovidRepository;
use App\Repositories\USRepository;
use Exception;

class RipsService {

    /**
     * Determina el tipo de rip
     * @param String $tipo_rips
     * @return Instance
     * @author David Peláez
     */
    public function determinarRips($tipo_rips){
        switch ($tipo_rips) {
            case 'AC':
                return new ACRepository;
                break;
            case 'AP':
                return new APRepository;
                break;
            case 'AM':
                return new AMRepository;
                break;
            case 'AT':
                return new ATRepository;
                break;
            case 'CT':
                return new CTRepository;
                break;
            case 'US':
                return new USRepository;
                break;
            case 'AF':
                return new AFRepository;
                break;
            case 'AC-COVID':
                return new ACCovidRepository;
                break;
            case 'AP-COVID':
                return new APCovidRepository;
                break;
            case 'AM-COVID':
                return new AMCovidRepository;
                break;
            case 'AT-COVID':
                return new ATCovidRepository;
                break;
            case 'CT-COVID':
                return new CTCovidRepository;
                break;
            case 'US-COVID':
                return new USCovidRepository;
                break;
            case 'AF-COVID':
                return new AFCovidRepository;
                break;
            default:
                throw new Exception("El tipo de rips no existe");
                break;
        }
    } 

}