<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Añade esta importación

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        // Eliminar usuarios existentes (opcional)
        // Usuario::truncate();

        // Crear usuario administrador
        Usuario::create([
            'correo' => 'andresmc@example.com',
            'password' => Hash::make('Andres123'),
            'idrol' => 2, // Rol de administrador
            'token' => Str::random(60) // Genera token de 60 caracteres
        ]);

        // Crear usuario normal
        Usuario::create([
            'correo' => 'nolasco@example.com',
            'password' => Hash::make('Nolasco123'),
            'idrol' => 2, // Rol de usuario normal
            'token' => Str::random(60)
        ]);

        // Opcional: Crear usuarios con tokens únicos usando factory
        // Usuario::factory()->count(10)->create();
    }
}