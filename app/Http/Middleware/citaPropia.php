<?php

namespace App\Http\Middleware;

use App\Modelos\citapaciente;
use Closure;
use Illuminate\Http\Request;

class citaPropia
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $cita_paciente = citapaciente::where('id', $request->id)->with('paciente')->first();
        if($cita_paciente->paciente->Num_Doc != $request->token ){
            return response()->json([
                'message' => 'Esta cita no te pertenece.',
            ], 401);
        } 
        return $next($request); 
    }
}
