<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConsultaCitas
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
        $tipos = ['telemedicina', 'whatsapp', 'firma'];
        
        if(!in_array($request->tipo, $tipos)){
            return response()->json('No ofresemos un servicio para este tipo de cita.', 401);
        }

        return $next($request);
    }
}
