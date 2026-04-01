<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario no tiene su token de la API en la sesión, lo regresamos al login
        if (!session()->has('api_token')) {
            return redirect()->route('login')->withErrors(['error' => 'Debes iniciar sesión para acceder.']);
        }

        // Si sí tiene el token, lo dejamos pasar al Dashboard
        return $next($request);
    }
}