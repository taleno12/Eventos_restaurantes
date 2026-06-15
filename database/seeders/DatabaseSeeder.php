<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN 1 ===
        User::factory()->create([
            'name' => 'Administrador Principal',
            'email' => 'kevintaleno17@gmail.com',      // ← Tu Gmail #1
            'password' => bcrypt(uniqid()),
            'google_id' => null,
            'email_verified_at' => now(),
        ]);

        // === ADMIN 2 ===
        User::factory()->create([
            'name' => 'Administrador Secundario',
            'email' => '15ulisesramirez@gmail.com',      // ← Tu Gmail #2 (socio, etc.)
            'password' => bcrypt(uniqid()),
            'google_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->call([
            DepartamentoSeeder::class,
        ]);
    }
}
