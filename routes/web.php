<?php

use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\DiaController;
use App\Http\Controllers\DocenteController;

use App\Http\Controllers\GrupoController;
use App\Http\Controllers\RolController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::get('/', function () {
    return view('auth.account-type-selector');
})->name('home');

// Selector de tipo de cuenta
Route::get('/account-selector', function () {
    return view('auth.account-type-selector');
})->name('account.selector');

// Rutas de login por tipo de cuenta
Route::get('/login/estudiante', function () {
    return view('livewire.auth.login-estudiante');
})->name('login.estudiante');

Route::get('/login/docente', [App\Http\Controllers\Auth\DocenteLoginController::class, 'showLoginForm'])->name('login.docente');
Route::post('/login/docente', [App\Http\Controllers\Auth\DocenteLoginController::class, 'login'])->name('docente.login');

Route::get('/login/administrativo', function () {
    return view('livewire.auth.login-administrativo');
})->name('login.administrativo');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('dashboard-docente', 'dashboard-docente')
    ->middleware(['auth', 'verified'])
    ->name('dashboard.docente');

Route::view('dashboard-estudiante', 'dashboard-estudiante')
    ->middleware(['auth', 'verified'])
    ->name('dashboard.estudiante');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');    
});

Route::middleware(['auth'])->group( function () {

    // Ruta para la bitacora
    Route::get('/bitacoras', [BitacoraController::class, 'index'])->name('bitacoras.index');
    
    //Rutas para el modulo docente
    Route::get('docentes/deletedIndex', [DocenteController::class, 'deletedIndex'])->name('docentes.deleted-index');
    Route::patch('docentes/reactivate/{id}', [DocenteController::class, 'reactivate'])->name('docentes.reactivar');
    Route::resource('docentes', DocenteController::class);

    //Rutas para el modulo administrativo
    Route::get('administrativos/deletedIndex', [AdministrativoController::class, 'deletedIndex'])->name('administrativos.deleted-index');
    Route::patch('administrativos/reactivate/{id}', [AdministrativoController::class, 'reactivate'])->name('administrativos.reactivar');
    Route::resource('administrativos', AdministrativoController::class);

    //Rutas para el modulo rol
    Route::get('roles/deletedIndex', [RolController::class, 'deletedIndex'])->name('roles.deleted-index');
    Route::patch('roles/reactivate/{id}', [RolController::class, 'reactivate'])->name('roles.reactivar');
    Route::resource('roles', RolController::class);

    //Rutas para el modulo dia
    Route::get('dias/deletedIndex', [DiaController::class, 'deletedIndex'])->name('dias.deleted-index');
    Route::patch('dias/reactivate/{id}', [DiaController::class, 'reactivate'])->name('dias.reactivar');
    Route::resource('dias', DiaController::class);

    //Rutas para modulo grupo
    Route::get('grupos/deletedIndex', [GrupoController::class, 'deletedIndex'])->name('grupos.deleted-index');
    Route::patch('grupos/reactivate/{id}', [GrupoController::class, 'reactivate'])->name('grupos.reactivar');
    Route::resource('grupos', GrupoController::class);

    //Rutas para modulo aula
    Route::get('aulas/deletedIndex', [AulaController::class, 'deletedIndex'])->name('aulas.deleted-index');
    Route::patch('aulas/reactivate/{id}', [AulaController::class, 'reactivate'])->name('aulas.reactivar');
    Route::resource('aulas', AulaController::class);

    //Rutas para modulo asignaturas
    Route::get('asignaturas/deletedIndex', [AsignaturaController::class, 'deletedIndex'])->name('asignaturas.deleted-index');
    Route::patch('asignaturas/reactivate/{id}', [AsignaturaController::class, 'reactivate'])->name('asignaturas.reactivar');
    Route::resource('asignaturas', AsignaturaController::class);

});