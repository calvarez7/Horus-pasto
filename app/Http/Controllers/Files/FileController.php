<?php

namespace App\Http\Controllers\Files;

use App\User;
use App\Modelos\Files;
use App\Modelos\Paciente;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Facturainicial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function setFiles(Request $request, citapaciente $citapaciente)
    {
        $pacientes = Paciente::select(['pacientes.*'])
            ->where('id', $citapaciente->Paciente_id)
            ->first();

        if (isset($pacientes)) {
            if ($files = $request->file('files')) {
                foreach ($files as $file) {
                    $name      = $file->getClientOriginalName();
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $guid      = $pacientes->Num_Doc . 'DT' . date("Y-m-d H:i:s.u") . '.' . $extension;
                    $pathdb    = '/storage/pacientes/' . $pacientes->Num_Doc . '/transcripcion/' . $guid;
                    $path      = '../storage/app/public/pacientes/' . $pacientes->Num_Doc . '/transcripcion/';
                    if ($file->move(public_path($path), $guid)) {
                        $files = Files::create([
                            'Name'            => $name,
                            'Path'            => $pathdb,
                            'CitaPaciente_id' => $citapaciente->id,
                        ]);
                    }
                }

                return response()->json([
                    'message' => 'Archivos guardados de manera exitosa',
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Paciente no encontrado',
            ], 404);
        }
    }

    public function getFilesByPaciente(citapaciente $citapaciente)
    {
        $files = Files::select('Files.*')
            ->join('cita_paciente', 'Files.CitaPaciente_id', 'cita_paciente.id')
            ->join('Pacientes', 'cita_paciente.Paciente_id', 'Pacientes.id')
            ->where('Pacientes.id', $citapaciente->Paciente_id)
            ->get();

        if (isset($files)) {
            return response()->json($files, 201);
        }

        return response()->json([
            'message' => 'Archivo no encontrado',
        ], 404);

    }

    public function import(Request $request)
    {
        $facturainicial = (new FastExcel)->import($request->data, function ($line) {
            return Facturainicial::create([
                'tipo'         => $line['tipo'],
                'numero'       => $line['numero'],
                'fecha'        => $line['fecha'],
                'cod_int'      => rtrim($line['cod_int']),
                'descripcion'  => $line['descripcion'],
                'presentacion' => $line['presentacion'],
                'nom_com'      => $line['nom_com'],
                'cum'          => $line['cum'],
                'lote'         => $line['lote'],
                'f_venc'       => $line['f_venc'],
                'laboratorio'  => $line['laboratorio'],
                'embalaje'     => rtrim($line['embalaje']),
                'cajas'        => rtrim($line['cajas']),
                'unidades'     => $line['unidades'],
                'valor'        => rtrim($line['valor']),
                'total'        => $line['total'],
                'nit'          => $line['nit'],
                'pedido'       => $line['pedido'],
                'usuario'      => auth()->user()->id,
            ]);

        });
        DB::select('exec dbo.sp_GeneraDatosFactura ' . auth()->user()->id);

        return response()->json([
            'message' => 'Factura Cargada con exito!',
        ], 201);
    }
}
