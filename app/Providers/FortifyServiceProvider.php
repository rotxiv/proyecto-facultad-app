<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\Administrativo;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
        
        // Autenticación personalizada según tipo de cuenta
        Fortify::authenticateUsing(function (Request $request) {
            $accountType = $request->input('account_type');
            
            switch ($accountType) {
                case 'estudiante':
                    return $this->authenticateEstudiante($request);
                case 'administrativo':
                    return $this->authenticateAdministrativo($request);
                default:
                    return null;
            }
        });
        

    }
    
    /**
     * Autenticar estudiante por carnet
     */
    private function authenticateEstudiante(Request $request)
    {
        $carnet = $request->input('carnet');
        $password = $request->input('password');
        
        // Buscar persona por carnet
        $persona = Persona::where('carnet', $carnet)->first();
        
        if (!$persona) {
            return null;
        }
        
        // Buscar usuario asociado (esto requeriría una relación estudiante)
        // Por ahora retornamos null ya que no tenemos tabla de estudiantes
        return null;
    }
    

    
    /**
     * Autenticar administrativo por email
     */
    private function authenticateAdministrativo(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        // Buscar usuario por email
        $user = User::where('email', $email)->first();
        
        if ($user && Hash::check($password, $user->password)) {
            // Verificar que tenga rol administrativo
            if ($user->isAdmin()) {
                return $user;
            }
        }
        
        return null;
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('auth.account-type-selector'));
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        Fortify::registerView(fn () => view('livewire.auth.register'));
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('livewire.auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
