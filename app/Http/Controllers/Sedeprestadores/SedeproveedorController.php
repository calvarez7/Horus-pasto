<?php

namespace App\Http\Controllers\Sedeprestadores;

use App\Modelos\Af;
use App\Modelos\Pqrsf;
use App\Modelos\Ipspqrsf;
use App\Modelos\Prestadore;
use Illuminate\Http\Request;
use App\Modelos\Serviciosede;
use App\Modelos\Sedeproveedore;
use App\Modelos\Asignado_cmedicas;
use App\Modelos\Historialservicio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SedeproveedorController extends Controller
{
    public function all(Request $request)
    {
        $prestador = (isset($request->Prestador_id)?$request->Prestador_id:auth()->user()->prestador_id);
        $sedeproveedor = Sedeproveedore::select(['Sedeproveedores.*', 'Municipios.Nombre as Municipio', 'Prestadores.Nombre as Nombreprestador'])
            ->join('Municipios', 'Sedeproveedores.Municipio_id', 'Municipios.id')
            ->join('Prestadores', 'Sedeproveedores.Prestador_id', 'Prestadores.id')
            ->where('Sedeproveedores.Estado_id', 1)
            ->where('Prestadores.id', $prestador)
            ->get();
        return response()->json($sedeproveedor, 200);
    }

    public function allproveedores()
    {
        $sedeproveedor = Sedeproveedore::select(['Sedeproveedores.*','Prestadores.nit'])
            ->join('Prestadores', 'Sedeproveedores.Prestador_id', 'Prestadores.id')
            ->where('Sedeproveedores.Estado_id', 1)
            ->where('Prestadores.Tipoprestador_id', 2)
            ->where('Prestadores.Estado_id', 1)
            ->get();
        return response()->json($sedeproveedor, 200);
    }

    public function proveedores()
    {
        $proveedores = Sedeproveedore::all(['id','Nombre']);
        return response()->json($proveedores, 200);
    }

    public function sedeproveedores()
    {
        $sedeproveedores = Sedeproveedore::select([
            'id','Codigo_habilitacion','Nombre',
            DB::raw("CONCAT(id,' ',Nombre) as NombreIPS"),
            ])
            ->distinct()
            ->get();
        return response()->json($sedeproveedores, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Codigo_habilitacion' => 'required|string|unique:Sedeproveedores',
            'Nombre'              => 'required|string',
            'Nivelatencion'       => 'required|string',
            'Correo1'             => 'required|string',
            'Telefono1'           => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input         = $request->all();
        $sedeproveedor = Sedeproveedore::create($input);

        return response()->json([
            'message' => 'Sede del prestador creada con exito!',
        ], 201);
    }

    public function getSedeId(Request $request)
    {
        $sedeproveedor = Sedeproveedore::select(['Sedeproveedores.*'])
            ->where('Sedeproveedores.Estado_id', 1)
            ->where('Prestadores.id', $request->Prestador_id)
            ->get();
        return response()->json($sedeproveedor, 200);
    }

    public function update(Request $request, Sedeproveedore $sedeproveedore)
    {
        $sedeproveedore->update($request->all());
        return response()->json([
            'message' => 'La sede del proveedor fue actualizada con exito!',
        ], 200);
    }

    public function disable(Sedeproveedore $sedeproveedore)
    {
        $sedeproveedore->update([
            'Estado_id' => 2,
        ]);
        return response()->json([
            'message' => '¡Sede deshabilitada con exito!',
        ], 200);
    }

    public function getSedePrestadorByName(Request $request)
    {
        $sedeproveedores = Sedeproveedore::select('*')
            ->where('Estado_id', 1)
            ->where('Nombre', 'LIKE', '%' . $request->nombre . '%')
            ->get();

        return response()->json($sedeproveedores, 200);
    }

    public function getSedePrestador(Request $request)
    {
        $sedeproveedores = Sedeproveedore::select('*')
            ->where('Estado_id', 1)
            ->get();

        return response()->json($sedeproveedores, 200);
    }

    public function getSedePrestadorPqrsf()
    {
        $ips = Sedeproveedore::getRepository()->getSedePrestadorPqrsf()->get();
        return response()->json($ips, 201);
    }

    public function pqrsfIps(Pqrsf $pqrsf){

        $pqrsfips = Ipspqrsf::select(['sedeproveedores.Nombre', 'sedeproveedores.id'])
        ->join('sedeproveedores', 'ipspqrsf.IPS_id', 'sedeproveedores.id')
        ->where('Pqrsf_id', $pqrsf->id)
        ->get();
        return response()->json($pqrsfips, 201);
    }

    public function acumuladoPrestador(Request $request){
        $validate = Validator::make($request->all(), [
            'prestadorId' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $prestadores = Prestadore::select([
            'Prestadores.id',
            'Prestadores.Nombre',
            'Prestadores.Nit',
            DB::raw("SUM(af.valor_Neto) as totalNeto"),
            DB::raw("COUNT(af.id) as totalFacturas"),
        ])
            ->join('sedeproveedores as sp','sp.prestador_id','Prestadores.id')
            ->join('paq_rips as pr','pr.Reps_id','sp.id')
            ->join('afs as af','af.paq_id','pr.id')
            ->where('pr.estado_id',7)
            ->where('af.created_at','>=',$request->fecha_inicio.' 00:00:00.000')
            ->where('af.created_at','<=',$request->fecha_fin.' 23:59:59.000')
            ->where('Prestadores.id', $request->prestadorId)
            ->whereNotin("af.id",function ($query) {
                $query->select('af_id')
                    ->from('asignado_cmedicas');
            })
            ->groupBy('Prestadores.id','Prestadores.Nombre','Prestadores.Nit')
            ->get();

        return response()->json($prestadores, 201);
    }

    public function facturaPrestador(Request $request){

        $afs = Af::select([
            'afs.id',
            'afs.numero_factura',
            'afs.valor_Neto'
        ])
            ->join('paq_rips as pr','pr.id','afs.paq_id')
            ->join('sedeproveedores as sp','sp.id','pr.Reps_id')
            ->join('Prestadores as p','p.id','sp.prestador_id')
            ->where('afs.created_at','>=',$request->fecha_inicio.' 00:00:00.000')
            ->where('afs.created_at','<=',$request->fecha_fin.' 23:59:59.000')
            ->where('p.id',$request->prestadorId)
            ->where('pr.estado_id',7)
            ->whereNotin("afs.id",function ($query) {
                $query->select('af_id')
                    ->from('asignado_cmedicas');
            })
            ->get();

        return response()->json($afs, 201);
    }

    public function getProveedores()
    {
        $proveedor = Sedeproveedore::getRepository()->getProveedores()->get();
        return response()->json($proveedor, 201);
    }

    /**
     * Obtiene el listado de las sedes, filtrables por proveedor
     * @param Request $request
     * @return Response
     * @author David Peláez
     */
    public function listar(Request $request){
        try {
            $sedes = Sedeproveedore::where('estado_id', 1)->whereProveedor($request->proveedor_id)->get();
            return response()->json($sedes, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function prestadoresRips(Request $request){
        $query = Sedeproveedore::select(['Sedeproveedores.*','Prestadores.nit','Prestadores.Nombre as prestador'])
            ->join('Prestadores', 'Sedeproveedores.Prestador_id', 'Prestadores.id')
            ->where('Prestadores.Tipoprestador_id', 1);
        if($request->nit){
            $query->where('Prestadores.nit', $request->nit);
        }
        if($request->habilitacion){
            $query->where('Sedeproveedores.Codigo_habilitacion','like', "%{$request->habilitacion}%");
        }
        if($request->nombre){
            $query->where('Sedeproveedores.Nombre','like', "%{$request->nombre}%");
        }
         $sedeproveedores = $query->paginate($request->items);

        return response()->json($sedeproveedores);
    }

    public function prestadores(){
        $prestadores = Prestadore::all();
        return response()->json($prestadores);
    }

    public function guardarRepsRips(Request $request){
        $sedePrestador = new Sedeproveedore();
        $mensaje = 'Sede creada!';
        if($request->id){
            $sedePrestador = Sedeproveedore::find($request->id);
            $mensaje = 'Sede Actualizada!';
        }
        $sedePrestador->Municipio_id = $request->municipio_id;
        $sedePrestador->Prestador_id = $request->prestador_id;
        $sedePrestador->Codigo_habilitacion = $request->codigoHabilitacion.'-'.$request->digito;
        $sedePrestador->Nombre = $request->nombre;
        $sedePrestador->Nivelatencion = $request->nivel;
        $sedePrestador->Correo1 = $request->email1;
        $sedePrestador->Correo2 = $request->email2;
        $sedePrestador->Telefono1 = $request->telefono1;
        $sedePrestador->Telefono2 = $request->telefono2;
        $sedePrestador->Direccion = $request->direccion;
        $sedePrestador->Estado_id = 1;
        $sedePrestador->Codigo = $request->codigoHabilitacion.$request->digito;
        $sedePrestador->save();
        return response()->json(['mensaje'=> $mensaje]);

    }

    public function guardarPrestadorRips(Request $request){
        $prestador = new Prestadore();
        $prestador->Tipoprestador_id = 1;
        $prestador->Nombre = $request->nombre;
        $prestador->Nit = $request->nit;
        $prestador->Direccion = $request->direccion;
        $prestador->Correo1 = $request->email1;
        $prestador->Correo2 = $request->email2;
        $prestador->Telefono1 = $request->telefono1;
        $prestador->Telefono2 = $request->telefono2;
        $prestador->Codigo_prestador = $request->codigoPrestador;
        $prestador->capitado = $request->capitado;
        $prestador->Estado_id = 1;
        $prestador->save();
        return response()->json(['mensaje'=> 'Prestador creado!']);

    }
}
