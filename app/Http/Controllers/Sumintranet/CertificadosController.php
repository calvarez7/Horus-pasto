<?php

namespace App\Http\Controllers\sumintranet;

use App\Http\Controllers\Controller;
use App\Modelos\Certificado;
use App\User;
use Illuminate\Http\Request;

class CertificadosController extends Controller
{
    public function auditoriaCertificado(Request $request)
    {
        $user = User::find($request->id)->toArray();
        $certidicado = Certificado::create([
            'num_doc' => $user['cedula'],
            'tipo_doc' => $user['nit'],
            'full_name' => $user['name'],
            'id_estadoafiliado' => $user['estado_user'],
        ]);
        return response()->json($certidicado, 200);
    }
}
