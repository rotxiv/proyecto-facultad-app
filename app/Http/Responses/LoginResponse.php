<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = auth()->user();

        registrar_bitacora(
            "El usuario : {$user->name} ha iniciado sesion."
        );

        // Redirigir según el rol del usuario
        if ($user->rol) {
            switch ($user->rol->nombre) {
                case 'Administrador':
                    return $request->wantsJson()
                        ? new JsonResponse([], 200)
                        : redirect()->intended('/dashboard');
                        
                case 'Docente':
                    return $request->wantsJson()
                        ? new JsonResponse([], 200)
                        : redirect()->intended('/dashboard-docente');
                        
                case 'Estudiante':
                    return $request->wantsJson()
                        ? new JsonResponse([], 200)
                        : redirect()->intended('/dashboard-estudiante');
                        
                default:
                    return $request->wantsJson()
                        ? new JsonResponse([], 200)
                        : redirect()->intended('/dashboard');
            }
        }
        
        // Redirección por defecto
        return $request->wantsJson()
            ? new JsonResponse([], 200)
            : redirect()->intended('/dashboard');
    }
}