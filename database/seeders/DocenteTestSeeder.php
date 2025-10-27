<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use App\Models\Persona;
use App\Models\Docente;
use Illuminate\Support\Facades\Hash;

class DocenteTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear rol de docente
        $rolDocente = Rol::firstOrCreate([
            'nombre' => 'Docente'
        ], [
            'descripcion' => 'Usuario docente con acceso a gestión de clases'
        ]);

        // Crear persona
        $persona = Persona::firstOrCreate([
            'carnet' => 'DOC001'
        ], [
            'nombre' => 'Dr. Juan Carlos Pérez',
            'sexo' => 'M',
            'telefono' => '70123456',
            'direccion' => 'Av. Banzer #123',
            'fecha_ingreso' => now()->format('Y-m-d')
        ]);

        // Crear docente
        $docente = Docente::firstOrCreate([
            'codigo' => 'DOC001'
        ], [
            'persona_id' => $persona->id,
            'correo' => 'docente@facultad.edu',
            'password' => Hash::make('docente123'),
            'carga_horaria' => 40,
            'estado' => true
        ]);

        // Crear usuario docente
        User::firstOrCreate([
            'email' => 'docente@facultad.edu'
        ], [
            'name' => 'Dr. Juan Carlos Pérez',
            'password' => Hash::make('docente123'),
            'rol_id' => $rolDocente->id,
            'email_verified_at' => now()
        ]);

        $this->command->info('Usuario docente creado exitosamente:');
        $this->command->info('Email: docente@facultad.edu');
        $this->command->info('Contraseña: docente123');
        $this->command->info('Código Docente: DOC001');
    }
}
