<?php

namespace App\Http\Controllers\Municipios;

use App\Http\Controllers\Controller;
use App\Modelos\Estado;
use App\Modelos\Municipio;
use App\Modelos\Secretaria;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function all()
    {
        $municipios = Municipio::all();
        return response()->json($municipios, 200);
    }

    public function listaMunicipioDepartamento(){
        $municipios = Municipio::select(['municipios.id','municipios.Nombre as municipio','d.Nombre as departamento',
        'municipios.codigo_Dane as codigoDaneM','d.codigo_Dane as codigoDaneD'])
            ->join('departamentos as d','d.id','municipios.Departamento_id')
            ->get();
        return response()->json($municipios, 200);

    }

    public function secretaria(){
        $secretarias = Secretaria::where('estado_id', 1)
            ->get();
        return response()->json($secretarias, 200);

    }

    public function DatosGeograficos($municipio){
        $datosgeograficos = Municipio::select(['d.Nombre as departamento','d.id as departamento_id',
        'municipios.codigo_Dane as codigoDaneM','d.codigo_Dane as codigoDaneD','s.Nombre as IpsAtencion','s.id as idIps'])
            ->join('departamentos as d','d.id','municipios.Departamento_id')
            ->leftjoin('sedeproveedores as s','s.Municipio_id','municipios.id')
            ->where('municipios.Nombre',$municipio)
            ->get();
        return response()->json($datosgeograficos, 200);
    }

    public function DatosGeosede($municipio){
        $datosgeograficos = Municipio::select(['d.Nombre as departamento','d.id as departamento_id',
        'municipios.codigo_Dane as codigoDaneM','d.codigo_Dane as codigoDaneD','s.Nombre as IpsAtencion','s.id as idIps'])
            ->join('departamentos as d','d.id','municipios.Departamento_id')
            ->join('sedeproveedores as s','s.Municipio_id','municipios.id')
            ->where('municipios.id',$municipio)
            ->where('s.Estado_id',  1)
            ->distinct()
            ->get();
        return response()->json($datosgeograficos, 200);
    }

}
