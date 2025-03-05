<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles) // Soporta múltiples roles
    {
        if (Auth::check() && in_array(Auth::user()->idRol, $roles)) {
            return $next($request);
        }

        // Verifica si la petición es AJAX
        if ($request->ajax()) {
            return response()->json(['error' => 'No tienes permisos para acceder.'], 403);
        }   

        // Si no es AJAX, redirige al home con un mensaje de error
        return redirect('/')->with('error', 'No tienes permisos para acceder.');
    }
}

