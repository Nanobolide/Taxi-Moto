<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create(); // 10 utilisateurs aléatoires

        // Tu peux aussi forcer un rôle précis :
        // User::factory()->create([
        //     'email' => 'admin@example.com',
        //     'role' => 'admin',
        //     'password' => bcrypt('password'), // mot de passe connu pour test
        // ]);
    }
}
