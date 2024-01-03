<?php

namespace App\Http\Controllers\MedicalBills;

use Illuminate\Http\Request;
use App\Modelos\Email_cmedicas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmailMedicalBillsController extends Controller
{
    public function all(){

        $emailPrestador = Email_cmedicas::select(['email_cmedicas.id', 'email_cmedicas.email', 'pr.Nombre', 'pr.Nit'])
        ->join('prestadores as pr', 'email_cmedicas.prestador_id', 'pr.id')
        ->get();

        return response()->json($emailPrestador, 201);

    }

    public function store(Request $request){

        $validate = Validator::make($request->all(), [
            'email'      => 'required',
            'prestador_id'       => 'required|unique:email_cmedicas'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $input = $request->all();
        Email_cmedicas::create($input);
        return response()->json([
            'message' => 'Email Prestador creado con exito!',
        ], 201);
        
    }

    public function update(Email_cmedicas $email_cmedica, Request $request){

        $email_cmedica->update([
            'email' => $request->email
        ]);
        
        return response()->json([
            'message' => 'Email actualizado con exito!',
        ], 200);

    }
}
