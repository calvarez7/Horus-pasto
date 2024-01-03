<?php

namespace App\Http\Controllers\RemisionProgramas;

use Illuminate\Http\Request;
use App\Modelos\Remisionprogramas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class RemisionProgramasController extends Controller
{
    public function listarProgramasPaciente($id)
    {
        $remisionProgramas = Remisionprogramas::select(
            'remisionprogramas.primerainfancia',
            'remisionprogramas.infancia',
            'remisionprogramas.adolescencia',
            'remisionprogramas.juventud',
            'remisionprogramas.adultez',
            'remisionprogramas.vejez',
            'remisionprogramas.maternoperinatal',
            'remisionprogramas.preconcepcional',
            'remisionprogramas.psicoprofilactico',
            'remisionprogramas.educativo',
            'remisionprogramas.lactanciamaterna',
            'remisionprogramas.cancerdecervix',
            'remisionprogramas.cancerdemama',
            'remisionprogramas.cancercolorectal',
            'remisionprogramas.cancerdeprostata',
            'remisionprogramas.vacunacion',
            'remisionprogramas.controlpospartoyreciennacido',
            'remisionprogramas.planificacionfamiliar',
            'remisionprogramas.riesgocardiovascular',
            'remisionprogramas.otrogrupoderiesgo',
            'remisionprogramas.cual',
            'u.name', 'u.apellido'
        )
        ->join('users as u', 'remisionprogramas.user_id', 'u.id')
        ->join('pacientes as p', 'remisionprogramas.paciente_id', 'p.id')
        ->where('p.id', $id)
        ->orderBy('remisionprogramas.id', 'desc')->first();
        return response()->json($remisionProgramas, 200);
    }

    public function guardar(Request $request)
    {   $request['user_id'] = Auth::user()->id;
        $remision = Remisionprogramas::create($request->all());
        return response()->json($remision, 200);
    }

    public function generarInforme(Request $request){

        $remisionProgramas = Remisionprogramas::select(
            'p.Ut',
            'p.Tipo_Doc',
            'p.Num_Doc',
            's.Nombre as Sede',
            DB::raw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) as NombreCompleto"),
            DB::raw("case remisionprogramas.primerainfancia when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end primerainfancia"),
            DB::raw("case remisionprogramas.infancia when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end infancia"),
            DB::raw("case remisionprogramas.adolescencia when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end adolescencia"),
            DB::raw("case remisionprogramas.juventud when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end juventud"),
            DB::raw("case remisionprogramas.adultez when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end adultez"),
            DB::raw("case remisionprogramas.vejez when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end vejez"),
            DB::raw("case remisionprogramas.maternoperinatal when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end maternoperinatal"),
            DB::raw("case remisionprogramas.preconcepcional when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end preconcepcional"),
            DB::raw("case remisionprogramas.psicoprofilactico when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end psicoprofilactico"),
            DB::raw("case remisionprogramas.educativo when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end educativo"),
            DB::raw("case remisionprogramas.lactanciamaterna when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end lactanciamaterna"),
            DB::raw("case remisionprogramas.cancerdecervix when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end cancerdecervix"),
            DB::raw("case remisionprogramas.cancerdemama when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end cancerdemama"),
            DB::raw("case remisionprogramas.cancercolorectal when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end cancercolorectal"),
            DB::raw("case remisionprogramas.cancerdeprostata when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end cancerdeprostata"),
            DB::raw("case remisionprogramas.vacunacion when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end vacunacion"),
            DB::raw("case remisionprogramas.controlpospartoyreciennacido when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end controlpospartoyreciennacido"),
            DB::raw("case remisionprogramas.planificacionfamiliar when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end planificacionfamiliar"),
            DB::raw("case remisionprogramas.riesgocardiovascular when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end riesgocardiovascular"),
            DB::raw("case remisionprogramas.otrogrupoderiesgo when 1 then 'Activo' when '0' then 'Desactivado' else 'Nunca' end otrogrupoderiesgo"),
            'remisionprogramas.cual',
            DB::raw("CONCAT(u.name,' ',u.apellido) as Usuario_Ingreso")
        )
        ->join('users as u', 'remisionprogramas.user_id', 'u.id') 
        ->join('pacientes as p', 'remisionprogramas.paciente_id', 'p.id')
        ->join('sedeproveedores as s', 's.id', 'p.IPS')
        ->whereBetween('remisionprogramas.created_at', [$request->fechaDesde.' 00:00:00',$request->fechaHasta.' 23:59:59']);

        return (new FastExcel($remisionProgramas->get()))->download('file.xls');
    }
}
