<?php

namespace App\Http\Middleware;

use App\Modelos\citapaciente;
use Closure;
use Illuminate\Http\Request;

class citaRespondida
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
        $cita_paciente = citapaciente::where('id', $request->id)->first();
        if($cita_paciente->confirmacion_cita){
            return response()->json([
                'message' => 'Ya respondiste a esta cita.',
            ], 401);
        }
        return $next($request);
    }
}
