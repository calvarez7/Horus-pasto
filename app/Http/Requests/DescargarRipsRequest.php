<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DescargarRipsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "sede" =>  'required|string',
            "tipo_rips" =>  'required|string',
            "entidad" =>  'required',
            "fecha_inicial" =>  'required|date',
            "fecha_final" =>  'required|date',
        ];
    }

    /**
     * 
     */
    public function failedValidation(Validator $validator){
        throw (new HttpResponseException(response()->json($validator->errors(), 422)));
    }
}
