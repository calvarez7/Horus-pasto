<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarTipoAnexoSaludOcupacionalRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'nombre' => 'required|string|unique:tipo_anexos,nombre,',
           'descripcion' => 'required|string'
        ];
    }
}
