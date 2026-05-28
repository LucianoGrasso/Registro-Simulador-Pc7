<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        // Verificar que sea admin o instructor. Si no es ninguno de los dos, lo bloqueamos.
        if (!Auth::user()->isAdmin() && Auth::user()->role !== 'instructor') {
            
            // Lo redirigimos al scanner porque es la única vista válida para un operador
            return redirect()->route('sesiones.scanner')
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        return $next($request);
    }
}