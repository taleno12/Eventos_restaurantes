<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Creamos un usuario de prueba para que puedas loguearte más adelante
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@turismo.ni',
            'password' => bcrypt('password'), // La contraseña será: password
        ]);

        // 2. Llamamos al seeder de los departamentos que creamos antes
        // Esto llenará Masaya, Managua, etc.
        $this->call([
            DepartamentoSeeder::class,
        ]);
    }
}