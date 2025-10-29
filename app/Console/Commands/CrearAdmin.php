<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Rol;
use App\Models\Persona;
use App\Models\Administrativo;


class CrearAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crear-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un usuario Administrador';
    

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Crear rol de administrador
        $rolAdmin = Rol::firstOrCreate([
            'nombre' => 'Administrador'
        ], [
            'descripcion' => 'Usuario con acceso completo al sistema'
        ]);

        // Crear persona
        $persona = Persona::firstOrCreate([
            'carnet' => 'ADM001'
        ], [
            'nombre' => 'Administrador del Sistema',
            'sexo' => 'M',
            'telefono' => '70000000',
            'direccion' => 'Oficina Principal',
            'fecha_ingreso' => now()->format('Y-m-d')
        ]);

        // Crear administrativo
        $administrativo = Administrativo::firstOrCreate([
            'codigo' => 'ADM001'
        ], [
            'persona_id' => $persona->id,
            'correo' => 'admin@facultad.edu',
            'estado' => true
        ]);

        // Crear usuario administrador
        $user = User::firstOrCreate([
            'email' => 'admin@facultad.edu'
        ], [
            'name' => 'Administrador',
            'password' => Hash::make('administrador12345'),
            'rol_id' => $rolAdmin->id,
            'administrativo_id' => $administrativo->id,
            'email_verified_at' => now()
        ]);

        if ($user->wasRecentlyCreated) {
            $this->info('✅ Usuario administrador creado correctamente.');
        } else {
            $this->warn('⚠️ Ya existe un usuario con ese correo. No se creó uno nuevo.');
        }

        return 0;
    }
}
