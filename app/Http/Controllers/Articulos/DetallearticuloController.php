<?php

namespace App\Http\Controllers\Articulos;

use App\Modelos\Cum;
use App\Modelos\Grupo;
use App\Modelos\Pqrsf;
use App\Modelos\Alerta;
use App\Modelos\Codesumi;
use App\Modelos\Titulare;
use App\Modelos\Auditoria;
use App\Modelos\LineaBase;
use App\Modelos\Prestadore;
use App\Modelos\TipoAlerta;
use App\Modelos\Tipocodigo;
use Illuminate\Http\Request;
use App\Modelos\UnidadMedida;
use App\Modelos\AlertaDetalle;
use App\Modelos\MensajesAlerta;
use App\Modelos\Detallearticulo;
use App\Modelos\GrupoTerapeutico;
use App\Modelos\Viadministracion;
use App\Modelos\FormaFarmaceutica;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modelos\SubgrupoTerapeutico;
use App\Modelos\Detallearticulospqrsf;
use App\Modelos\CodesumiViadministracion;
use Illuminate\Support\Facades\Validator;
use App\Modelos\PrecioProveedorMedicamento;
use App\Modelos\HistoricoPrecioProveedorMedicamento;
use Illuminate\Http\Response;
use Rap2hpoutre\FastExcel\FastExcel;

class DetallearticuloController extends Controller
{

    public function all()
    {
        $detallearticulo = Detallearticulo::select([
            'Detallearticulos.*', 'Subgrupos.Nombre as NombreSubgrupo', 'Codesumis.Codigo as CodigoSumi',
            'Codesumis.Nombre as NombreSumi', 'Estados.Nombre as estadoNombre', 'titulares.nombre as nombreTitular'
            ])
            ->join('Subgrupos', 'Detallearticulos.Subgrupo_id', 'Subgrupos.id')
            ->join('Codesumis', 'Detallearticulos.Codesumi_id', 'Codesumis.id')
            ->join('Estados', 'Detallearticulos.Estado_id', 'Estados.id')
            ->leftjoin('titulares', 'Detallearticulos.titular_id', 'titulares.id')
            ->where('Subgrupos.Estado_id', 1)
            ->where('Codesumis.Estado_id', 1)
            ->get();

        return response()->json($detallearticulo, 201);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Cum_Validacion' => 'required|string|unique:Detallearticulos',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input           = $request->all();
        $detallearticulo = Detallearticulo::create($input);
        return response()->json([
            'messages' => 'Detallearticulo creado con exito!',
        ], 201);
    }

    public function update(Request $request, Detallearticulo $detallearticulo)
    {

        if(isset($request->nombreTitular['id'])){
            $request->merge(['titular_id' => $request->nombreTitular['id']]);
        }
        $detallearticulo->update($request->all());

        return response()->json([
            'message' => 'Detalle del articulo actualizado con exito!',
        ]);
    }

    public function pqrsfDetallearticulos(Pqrsf $pqrsf)
    {
        $pqrsfdetallearticulos = Detallearticulospqrsf::select(['Detallearticulos.Producto', 'Detallearticulos.id'])
        ->join('Detallearticulos', 'detallearticulospqrsf.Detallearticulo_id', 'Detallearticulos.id')
        ->where('Pqrsf_id', $pqrsf->id)
        ->get();
        return response()->json($pqrsfdetallearticulos, 201);
    }

    public function detalle_medicamentos()
    {
        $detalle_medicamentos = Detallearticulo::select('id', 'Producto')
        ->where('Subgrupo_id', 1)
        ->get();

        return response()->json($detalle_medicamentos, 201);
    }

    public function detalle_insumo_dispositivo()
    {
        $insumo_dispositivo = Detallearticulo::select('id', 'Producto')
        ->whereIn('Subgrupo_id', [2,3])
        ->get();

        return response()->json($insumo_dispositivo, 201);
    }

    public function getitulares(){

        $titulares = Titulare::where('estado_id', 1)->get();
        return response()->json($titulares, 201);

    }

    public function getMedicamentos(){
        $detallearticulo = Codesumi::select(["codesumis.*","e.Nombre as estado"])
            ->with(['detallearticulos' => function($query){
                $query->select(["detallearticulos.*",'t.nombre as titular',"pc.nombre as presentacion",'detallearticulos.codigo as Codigo','co.Codigo as codigoSumi'])
                    ->leftjoin('titulares as t','t.id','detallearticulos.titular_id')
                    ->join('codesumis as co','co.id', 'detallearticulos.Codesumi_id')
                    ->leftjoin('cums as c','c.Cum_Validacion','detallearticulos.Cum_Validacion')
                    ->leftjoin('presentacion_comercials as pc','pc.id','detallearticulos.presentacioncomercial_id')
                    ->distinct();
        },'viaadministracion'])
            ->join('estados as e','e.id','codesumis.Estado_id')
//            ->where('codesumis.Tipocodesumi_id',1)
            ->get();

                return response()->json($detallearticulo, 201);

    }

    public function getProveedorMedicamentos(){

        $proveedorMedicamentos = Prestadore::select(['sp.Nombre', 'prestadores.Nit', 'sp.id'])
        ->join('sedeproveedores as sp', 'prestadores.id', 'sp.Prestador_id')
        ->where('prestadores.Tipoprestador_id', 2)
        ->get();

        return response()->json($proveedorMedicamentos, 201);

    }

    public function getGrupos(){
        $grupos = Grupo::select(["id as grupo_id","Nombre"])->whereNotIn('id',[2])->get();
        return response()->json($grupos, 201);
    }
    public function getTipos(){
        $tipos = Tipocodigo::select(["id as tipo_id","Nombre"])->whereNotIn('id',[2,3,4,5,6])->get();
        return response()->json($tipos);
    }
    public function getGruposTerapeuticos(){
        $gruposTerapeutico = GrupoTerapeutico::Select(['id as grupoTerapeutico_id','nombre'])->get();
        return response()->json($gruposTerapeutico);
    }
    public function getSubGruposTerapeuticos(){
        $SubGruposTerapeutico = SubgrupoTerapeutico::Select(['id as SubGrupoTerapeutico_id','nombre'])->get();
        return response()->json($SubGruposTerapeutico);
    }
    public function getFormasFarmaceuticas(){
        $formasFarmaceuticas = FormaFarmaceutica::Select(['id as formaFarmaceutica_id','nombre'])->get();
        return response()->json($formasFarmaceuticas);
    }
    public function getViasAdministracion(){
        $viasAdministracion = Viadministracion::all();
        return response()->json($viasAdministracion);
    }
    public function getLineasBase(){
        $lineasBase = LineaBase::select(["id as lineaBase_id","nombre"])->get();
        return response()->json($lineasBase);
    }
    public function getUnidadMedida(){
        $unidadMedida = UnidadMedida::all();
        return response()->json($unidadMedida);
    }
    public function getCums($expediente){
        $cums = Cum::select(['Cum_Validacion', 'Producto', 'Titular'])->where('Expediente','LIKE',"%".$expediente."%")->orWhere('Producto','LIKE',"%".$expediente."%")->distinct('Cum_Validacion')->get();
        return response()->json($cums);
    }
    public function findCum($cumValidacion){
        $cum = Cum::select(["t.id as titular_id","cums.Titular","cums.Principio_Activo","cums.Registro_Sanitario",
        "cums.Fecha_Vencimiento","cums.Descripción_Comercial","cums.Estado_Cum","cums.Muestra_Medica","cums.Estado_Registro","cums.Producto"])->where('Cum_Validacion',$cumValidacion)
        ->join('titulares as t','t.nombre','cums.Titular')->first();
        return response()->json($cum);
    }
    public function saveCodesumi(Request $request, $tipo){

        $mensaje = "Codigo Sumi creado exitosamente";
        $type = 'success';
        $codesumi = new Codesumi();
        $request->merge(['Nombre' => strtoupper($request->Nombre),'Codigo' => strtoupper($request->Codigo),'Requiere_autorizacion' => ($request->Requiere_autorizacion == 1?'SI':'NO')]);
        if($tipo == 1){
            $codesumi->where('Codigo',strtoupper($request->Codigo))
                ->update($request->except(['vias','viaadministracion','detallearticulos','estado','id', 'created_at', 'updated_at']));
            $codesumi = Codesumi::where('Codigo',strtoupper($request->Codigo))->first();
            CodesumiViadministracion::where('codesumi_id', $codesumi->id)->delete();
            foreach ($request->vias as $via){
                CodesumiViadministracion::create(['codesumi_id'=> $codesumi->id,'viadministracion_id'=>$via]);
            }
            $mensaje = "Codigo Sumi actulizado exitosamente";

        }else{
            $validarCodesumi = Codesumi::where('Codigo',$request->Codigo)->first();
            if(!$validarCodesumi){
                $codesumi->create($request->except(['vias','created_at', 'updated_at']));
                $codesumi = Codesumi::where('Codigo',strtoupper($request->Codigo))->first();
                foreach ($request->vias as $via){
                    CodesumiViadministracion::create(['codesumi_id'=> $codesumi->id,'viadministracion_id'=>$via]);
                }
            }else{
                $mensaje = "Codigo Sumi ya se encuentra registrado";
                $type = 'error';
            }
        }

        return response()->json([
            'message' => $mensaje,
            'type' => $type
        ], 200);

    }

    public function saveDetalle($tipo,Request $request){
        $mensaje = "Detalle creado exitosamente";
        $type = 'success';
        $detalleArticulo = new Detallearticulo();
        $titular = Titulare::where('nombre',$request->titular)->first();
        $codesumi = Codesumi::where('Codigo',$request->codigoSumi)->first();
        $cum = Cum::where('Cum_Validacion',$request->Cum_Validacion)->first();
        $request->merge([
            'titular_id'=>$titular->id,
            'Estado_id'=> ($request->Estado_id === true?1:2),
            'Acuerdo_228'=> ($request->Acuerdo_228 === true?"SI":"NO"),
            'Alto_Costo'=> ($request->Alto_Costo === true?"SI":"NO"),
            'Regulado'=> ($request->Regulado === true?"SI":"NO"),
            'Requiere_autorizacion' => ($request->Requiere_autorizacion == 1?'SI':'NO'),
            'Codesumi_id'=>$codesumi->id,
            'Subgrupo_id'=>1,
            'Producto'=> $codesumi->Nombre,
            'Expediente'=> $cum->Expediente,
            'Invima' => $request->Registro_Sanitario,
            'Fecha_Expedicion' => $cum->Fecha_Expedicion,
            'Estado_Registro' => $cum->Estado_Registro,
            'Expediente_Cum' => $cum->Expediente_Cum,
            'Consecutivo' => $cum->Consecutivo,
            'Cantidad_Cum' => $cum->Cantidad_Cum,
            'Estado_Cum' => $cum->Estado_Cum,
            'Fecha_Activo' => $cum->Fecha_Activo,
            'Fecha_Inactivo' => $cum->Fecha_Inactivo,
            'Unidad' => $cum->Unidad,
            'Atc' => $cum->Atc,
            'Descripcion_Atc' => $cum->Descripcion_Atc,
            'Via_Administracion' => $cum->Via_Administracion,
            'Concentracion' => $cum->Concentracion,
            'Unidad_Medida' => $cum->Unidad_Medida,
            'Cantidad' => $cum->Cantidad,
            'Unidad_Referencia' => $cum->Unidad_Referencia,
            'Forma_Farmaceutica' =>$cum->Forma_Farmaceutica,
            'CUM_completo' => $cum->Expediente_Cum,
            'Cum_Validacion'=> $cum->Cum_Validacion,
            'Activo_HORUS' => ($request->Estado_id === true?'SI':'NO'),
            'Nivel_Ordenamiento' => $codesumi->Nivel_Ordenamiento,
            'Cantidadmaxordenar' => $codesumi->Cantidadmaxordenar,
            'Frecuencia' => $codesumi->Frecuencia,
            'unidad_compra' => $cum->Unidad_Referencia,
            'presentacion_comercial' => $cum->Descripción_Comercial,
            'Descripcion_Comercial' => $cum->Producto,
            'codigo' => $codesumi->Codigo."-".$titular->id
        ]);
        if($tipo == 1){
            $detalleArticulo->where('id',$request->id)
                ->update($request->except(['codigoSumi','titular','Estado','id',"presentacion","Codigo","codigoSumi"]));
            $mensaje = "Detalle Actualizado exitosamente";
        }else{
            $validarDetalle = Detallearticulo::where('Codesumi_id',$request->Codesumi_id)->where('Cum_Validacion',$request->Cum_Validacion)->first();
            if(!$validarDetalle){
                $detalleArticulo->create($request->except(['codigoSumi','titular','Estado','codigo']));
            }else{
                $mensaje = "Detalle ya se encuentra registrado";
                $type = 'error';
            }
        }

        return response()->json([
            'message' => $mensaje,
            'type' => $type
        ], 200);

    }

    public function saveDetallePrecio(Request $request){

        $existe = PrecioProveedorMedicamento::where('sedeproveedore_id', $request->sedeproveedore_id)
        ->where('detallearticulo_id', $request->detallearticulo_id)
        ->first();

        if($existe){
            return response()->json([
                'message' => 'Precio del detalle ya existe!',
            ], 402);
        }else{
            PrecioProveedorMedicamento::create($request->all());
            return response()->json([
                'message' => 'Precio del detalle creado con exito!',
            ], 201);
        }

    }

    public function getDetallePrecio($detalle){

        $preciosDetalle = PrecioProveedorMedicamento::select(['precio_proveedor_medicamentos.*', 'sp.Nombre'])
        ->join('sedeproveedores as sp', 'precio_proveedor_medicamentos.sedeproveedore_id', 'sp.id')
        ->where('detallearticulo_id', $detalle)
        ->get();

        return response()->json($preciosDetalle);

    }

    public function editDetallePrecio(Request $request){

        PrecioProveedorMedicamento::where('id', $request->id)
        ->update([
            'precio_unidad' => $request->precio_unidad,
            'iva'           => $request->iva,
            'iva_facturacion'  => $request->iva_facturacion,
            'precio_venta'  => $request->precio_venta,
        ]);

        if($request->precioDiferente == true){

            $historico = HistoricoPrecioProveedorMedicamento::where('sedeproveedore_id', $request->sedeproveedore_id)
            ->where('detallearticulo_id', $request->detallearticulo_id)
            ->latest()
            ->first();

            $resultado = $historico->precio_unidad <=> $request->precio_unidad;

            if($resultado !== 0){
                HistoricoPrecioProveedorMedicamento::create([
                    "precio_unidad" => $request->precio_unidad,
                    "sedeproveedore_id" => $request->sedeproveedore_id,
                    "detallearticulo_id" => $request->detallearticulo_id
                ]);
            }

        }

        Auditoria::create([
            'Descripcion'        => 'Modifico',
            'Tabla'              => 'precio_proveedor_medicamentos',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $request->id,
            'Motivo'             => 'Precio Unidad:'.$request->precio_unidad.' Iva:'.$request->iva.' Iva Facturacion:'.$request->iva_facturacion.' Precio Venta:'.$request->precio_venta
        ]);

        return response()->json([
            'message' => 'Precio del detalle editado con exito!',
        ], 201);

    }

    public function createMensaje(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'titulo'        => 'required|string',
            'mensaje'       => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        MensajesAlerta::create([
            'titulo'        => $request->titulo,
            'Mensaje'       => $request->mensaje,
            'usuario_id'    => auth()->user()->id,
            'Estado_id'     => 1,
        ]);
        return response()->json([
            'message' => 'Mensaje creado con exito!',
        ], 201);
    }

    public function getMensajes()
    {
        $mensaje_alerta = MensajesAlerta::select('mensajes_alertas.*',
        DB::raw("CONCAT(users.name,' ',users.apellido) as name"),'Estados.Nombre as estados')
        ->join('Estados', 'mensajes_alertas.Estado_id', 'Estados.id')
        ->join('users', 'mensajes_alertas.usuario_id', 'users.id')->get();
        return response()->json($mensaje_alerta, 200);
    }

    public function updateMensaje(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'titulo'        => 'required|string',
            'Mensaje'       => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $mensaje = MensajesAlerta::where('id',$request->id)->first();
        $mensaje->update([
            'titulo'        => $request->titulo,
            'Mensaje'       => $request->Mensaje,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Mensaje Actualizado con exito!',
        ], 201);
    }

    public function disableMensaje($id)
    {
        $mensaje = MensajesAlerta::where('id',$id)->first();
        $mensaje->update([
            'Estado_id'     => 2,
            'usuario_id'    => auth()->user()->id,
        ]);

        $alertas = AlertaDetalle::where('mensaje_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 2,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Mensaje Inactivo con exito!',
        ], 201);
    }

    public function enableMensaje($id)
    {
        $mensaje = MensajesAlerta::where('id',$id)->first();
        $mensaje->update([
            'Estado_id'     => 1,
            'usuario_id'    => auth()->user()->id,
        ]);
        $alertas = AlertaDetalle::where('mensaje_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 1,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Mensaje Activo con exito!',
        ], 201);
    }

    public function createTipo(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        TipoAlerta::create([
            'Nombre'        => $request->Nombre,
            'usuario_id'    => auth()->user()->id,
            'Estado_id'     => 1,
        ]);
        return response()->json([
            'message' => 'Tipo de Alerta creado con exito!',
        ], 201);
    }

    public function getTiposAlertas()
    {
        $tipos_alerta = TipoAlerta::select('tipo_alertas.*',
        DB::raw("CONCAT(users.name,' ',users.apellido) as name"),'Estados.Nombre as estados')
        ->join('Estados', 'tipo_alertas.Estado_id', 'Estados.id')
        ->join('users', 'tipo_alertas.usuario_id', 'users.id')->get();
        return response()->json($tipos_alerta, 200);
    }

    public function updateTipo(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre'        => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $mensaje = TipoAlerta::where('id',$request->id)->first();
        $mensaje->update([
            'Nombre'        => $request->Nombre,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Tipo de Alerta Actualizada con exito!',
        ], 201);
    }

    public function disableTipos($id)
    {
        $mensaje = TipoAlerta::where('id',$id)->first();
        $mensaje->update([
            'Estado_id'     => 2,
            'usuario_id'    => auth()->user()->id,
        ]);

        $alertas = AlertaDetalle::where('tipo_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 2,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Tipo de Alerta Inactivo con exito!',
        ], 201);
    }

    public function enableTipo($id)
    {
        $mensaje = TipoAlerta::where('id',$id)->first();
        $mensaje->update([
            'Estado_id'     => 1,
            'usuario_id'    => auth()->user()->id,
        ]);

        $alertas = AlertaDetalle::where('tipo_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 1,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Tipo de Alerta Activo con exito!',
        ], 201);
    }

    public function getPrincipioActivo()
    {
        $principioActivo = Detallearticulo::select('Descripcion_Atc as medicamento')
        ->where('Estado_id',1)
        ->distinct()
        ->get();

        foreach ($principioActivo as $principioActivos) {
            $medicamento[] = utf8_encode($principioActivos->medicamento);
        }

        return response()->json($medicamento, 200);

    }

    public function createAlerta(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'mensaje_id'    => 'required',
            'tipo_id'       => 'required',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $alerta = Alerta::where('id',$request->alertas_id)->first();
            if($alerta->Estado_id == 1){
                AlertaDetalle::create([
                        'mensaje_id'    => $request->mensaje_id,
                        'interaccion'  => $request->interaccion,
                        'tipo_id'       => $request->tipo_id,
                        'alertas_id'    => $request->alertas_id,
                        'usuario_id'    => auth()->user()->id,
                        'Estado_id'     => 1,
                    ]);
                    return response()->json([
                        'message' => 'Alerta creada con exito!',
                    ], 201);
            }else{
                return response()->json([
                    'msg' => 'No se puede crear la alerta, ya que esta inactivo la molecula principal',
                ], 402);
            }

    }

    public function createAlertaMedicamento(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'mensaje_id'    => 'required',
            'tipo_id'       => 'required',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $alerta = Alerta::where('id',$request->alertas_id)->first();
            if($alerta->Estado_id == 1){
                AlertaDetalle::create([
                        'mensaje_id'    => $request->mensaje_id,
                        'tipo_id'       => $request->tipo_id,
                        'alertas_id'    => $request->alertas_id,
                        'usuario_id'    => auth()->user()->id,
                        'Estado_id'     => 1,
                    ]);
                    return response()->json([
                        'message' => 'Alerta creada con exito!',
                    ], 201);
            }else{
                return response()->json([
                    'msg' => 'No se puede crear la alerta, ya que esta inactivo la molecula principal',
                ], 402);
            }

    }

    public function updatePrincipal(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'principal'     => 'required|string',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $alerta = Alerta::where('id',$request->id)->first();
        $alerta->update([
            'principal'     => $request->principal,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Alerta Actualizada con exito!',
        ], 201);
    }

    public function enablePrincipal($id)
    {
        $alerta = Alerta::where('id',$id)->first();
        $alerta->update([
            'Estado_id'     => 1,
            'usuario_id'    => auth()->user()->id,
        ]);
        $alertas = AlertaDetalle::where('alertas_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 1,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Molecula Principal Activa con exito!',
        ], 201);
    }

    public function disablePrincipal($id)
    {
        $alerta = Alerta::where('id',$id)->first();
        $alerta->update([
            'Estado_id'     => 2,
            'usuario_id'    => auth()->user()->id,
        ]);

        $alertas = AlertaDetalle::where('alertas_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 2,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Molecula Principal Inactiva con exito!',
        ], 201);
    }

    public function createPrincipal(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'principal'     => 'required|string',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

            Alerta::create([
                'principal'     => $request->principal,
                'usuario_id'    => auth()->user()->id,
                'Estado_id'     => 1,
        ]);
        return response()->json([
            'message' => 'Principal creado con exito!',
        ], 201);
    }

    public function getPrincipal()
    {
        $alertas = Alerta::select('alertas.*',DB::raw("CONCAT(users.name,' ',users.apellido) as name"),'Estados.Nombre as estados')
        ->join('Estados', 'alertas.Estado_id', 'Estados.id')
        ->join('users', 'alertas.usuario_id', 'users.id')
        ->whereNull('alertas.codesumis_id')
        ->get();
        return response()->json($alertas, 200);
    }

    public function getHistorico($id)
    {
        $alertas = AlertaDetalle::select('alerta_detalles.*','tipo_alertas.Nombre as tipoAlerta','mensajes_alertas.Mensaje','Estados.Nombre as estados',
        DB::raw("CONCAT(users.name,' ',users.apellido) as name"))
        ->join('tipo_alertas','tipo_alertas.id','alerta_detalles.tipo_id')
        ->join('mensajes_alertas','mensajes_alertas.id','alerta_detalles.mensaje_id')
        ->join('Estados', 'alerta_detalles.Estado_id', 'Estados.id')
        ->join('users', 'alerta_detalles.usuario_id', 'users.id')
        ->where('mensajes_alertas.Estado_id', 1)
        ->where('tipo_alertas.Estado_id', 1)
        ->where('alerta_detalles.alertas_id',$id)
        ->get();

        return response()->json($alertas, 200);
    }

    public function disableHistoricoAlertas($id)
    {
        $mensaje = AlertaDetalle::where('id',$id)->first();
        $mensaje->update([
            'Estado_id'     => 2,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Alerta Inactiva con exito!',
        ], 201);
    }

    public function disableHistoricoAlertasMedicamento($id)
    {
        $mensaje = AlertaDetalle::where('id',$id)->first();
        $mensaje->update([
            'Estado_id'     => 2,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Alerta Inactiva con exito!',
        ], 201);
    }

    public function enableHistoricoAlertas($id)
    {
        $alertaDetalle = AlertaDetalle::where('id',$id)->first();
        $tipoAlerta = TipoAlerta::where('id',$alertaDetalle->tipo_id)->first();
        $mensaje = MensajesAlerta::where('id',$alertaDetalle->mensaje_id)->first();
        $alerta = Alerta::where('id',$alertaDetalle->alertas_id)->first();
        if($alerta->Estado_id == 2 || $tipoAlerta->Estado_id == 2 || $mensaje->Estado_id == 2){
            return response()->json([
                'msg' => 'No se puede activar, el mensaje, le tipo o la molecula principal se encuentran inactivas',
            ], 402);
        }else{
            $alertaDetalle->update([
            'Estado_id'     => 1,
            'usuario_id'    => auth()->user()->id,
            ]);
            return response()->json([
                'message' => 'Alerta Activada con exito!',
            ], 201);
        }

    }

    public function enableHistoricoAlertasMedicamento($id)
    {
        $alertaDetalle = AlertaDetalle::where('id',$id)->first();
        $tipoAlerta = TipoAlerta::where('id',$alertaDetalle->tipo_id)->first();
        $mensaje = MensajesAlerta::where('id',$alertaDetalle->mensaje_id)->first();
        $alerta = Alerta::where('id',$alertaDetalle->alertas_id)->first();
        if($alerta->Estado_id == 2 || $tipoAlerta->Estado_id == 2 || $mensaje->Estado_id == 2){
            return response()->json([
                'msg' => 'No se puede activar, el mensaje, le tipo o la molecula principal se encuentran inactivas',
            ], 402);
        }else{
            $alertaDetalle->update([
            'Estado_id'     => 1,
            'usuario_id'    => auth()->user()->id,
            ]);
            return response()->json([
                'message' => 'Alerta Activada con exito!',
            ], 201);
        }
    }

    public function updateAlerta(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'mensaje_id'    => 'required',
            'tipo_id'       => 'required',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $alerta = AlertaDetalle::where('id',$request->id)->first();
        $alerta->update([
            'mensaje_id'    => $request->mensaje_id,
            'interaccion'  => $request->interaccion,
            'tipo_id'       => $request->tipo_id,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Alerta Actualizada con exito!',
        ], 201);
    }

    public function updateAlertaMedicamento(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'mensaje_id'    => 'required',
            'tipo_id'       => 'required',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $alerta = AlertaDetalle::where('id',$request->id)->first();
        $alerta->update([
            'mensaje_id'    => $request->mensaje_id,
            'tipo_id'       => $request->tipo_id,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Alerta Actualizada con exito!',
        ], 201);
    }


    public function getCodesumi()
    {
        $detallearticulo = Codesumi::select(["codesumis.id as medicamento_id","codesumis.Nombre as medicamento"])
            ->join('estados as e','e.id','codesumis.Estado_id')
            ->where('codesumis.Estado_id',1)
            ->get();

                return response()->json($detallearticulo, 201);
    }

    public function createCodesumi(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'codesumi'     => 'required',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

            Alerta::create([
                'codesumis_id'     => $request->codesumi,
                'usuario_id'    => auth()->user()->id,
                'Estado_id'     => 1,
        ]);
        return response()->json([
            'message' => 'Medicamento / Producto creado con exito!',
        ], 201);
    }

    public function getCodesumiAlerta()
    {
        $alertas = Alerta::select('alertas.*','codesumis.Nombre as medicamento',DB::raw("CONCAT(users.name,' ',users.apellido) as name"),'Estados.Nombre as estados')
        ->join('Estados', 'alertas.Estado_id', 'Estados.id')
        ->join('users', 'alertas.usuario_id', 'users.id')
        ->join('codesumis','codesumis.id','alertas.codesumis_id')
        ->whereNull('alertas.principal')
        ->get();
        return response()->json($alertas, 200);
    }

    public function updateCodesumi(request $request)
    {
        $validate = Validator::make($request->all(), [
            'medicamento_id'     => 'required',
            ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $alerta = Alerta::where('id',$request->id)->first();
        $alerta->update([
            'codesumis_id'     => $request->medicamento_id,
            'usuario_id'    => auth()->user()->id,
        ]);
        return response()->json([
            'message' => 'Alerta Actualizada con exito!',
        ], 201);
    }

    public function disableCodesumi($id)
    {
        $alerta = Alerta::where('id',$id)->first();
        $alerta->update([
            'Estado_id'     => 2,
            'usuario_id'    => auth()->user()->id,
        ]);

        $alertas = AlertaDetalle::where('alertas_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 2,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Medicamento Inactiva con exito!',
        ], 201);
    }

    public function enableCodesumi($id)
    {
        $alerta = Alerta::where('id',$id)->first();
        $alerta->update([
            'Estado_id'     => 1,
            'usuario_id'    => auth()->user()->id,
        ]);
        $alertas = AlertaDetalle::where('alertas_id',$id)->get();
        foreach ($alertas as $alerta) {
            $alerta->update([
                'Estado_id'     => 1,
                'usuario_id'    => auth()->user()->id,
            ]);
        }
        return response()->json([
            'message' => 'Medicamento / Producto Activa con exito!',
        ], 201);
    }

    public function exportVademecum(Request $request)
    {
        try {
            $appointments = Collect(DB::select("exec dbo.EntradasxFactura ?,?", [$request->fecha_inicio,$request->fecha_fin]));
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('vademecum.xls');

        } catch (\Throwable $th) {
            return response()->json([
                'erro' => $th->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
