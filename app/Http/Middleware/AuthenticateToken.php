<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class AuthenticateToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $userId = $request->header('X-User-Id');

        if ($this->isValidToken($userId, $token)) {
            return $next($request);
        }

        throw new UnauthorizedException('Token inválido o vencido');
    }

    private function isValidToken($userId, $token)
    {
        $storedToken = DB::table('tokens')->where('user_id', $userId)->first();

        if ($storedToken && $storedToken->token === $token) {
            // Verificar que el token no esté vencido
            // Aquí puedes agregar tu lógica para validar la vigencia del token
            // Por ejemplo, si el token tiene una fecha de expiración y es menor a la fecha actual, se considera vencido
            // Si el token está vencido, puedes retornar
            if ($this->isTokenExpired($storedToken)) {
                return false;
            }

            return true;
        }

        return false;
    }

    private function isTokenExpired($token)
    {
        // Aquí puedes agregar tu lógica para verificar si el token está vencido
        // Por ejemplo, si el token tiene una fecha de expiración y es menor a la fecha actual, se considera vencido
        // Si el token está vencido, puedes retornar false para indicar que no es válido

        return false; // Cambiar a true si el token está vencido
    }
}