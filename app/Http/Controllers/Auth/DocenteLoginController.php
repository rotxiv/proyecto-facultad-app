<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Validation\ValidationException;

class DocenteLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('livewire.auth.login-docente');
    }

    public function login(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
            'password' => 'required|string',
        ]);

        $codigo = $request->input('codigo');
        $password = $request->input('password');

        // Buscar docente por código
        $docente = Docente::where('codigo', $codigo)->where('estado', true)->first();

        if (!$docente) {
            throw ValidationException::withMessages([
                'codigo' => ['El código de docente no existe.'],
            ]);
        }

        // Verificar contraseña
        if (!$docente->password || !Hash::check($password, $docente->password)) {
            throw ValidationException::withMessages([
                'password' => ['La contraseña es incorrecta.'],
            ]);
        }

        // Buscar o crear usuario asociado
        $user = User::firstOrCreate([
            'email' => $docente->correo
        ], [
            'name' => $docente->persona->nombre ?? 'Docente',
            'password' => $docente->password,
            'rol_id' => Rol::where('nombre', 'Docente')->first()?->id,
            'email_verified_at' => now()
        ]);

        // Iniciar sesión
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended('/dashboard-docente');
    }
}
