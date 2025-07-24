<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the Professor user
        User::factory()->create([
            'name' => 'Professeur Test',
            'email' => 'prof@insuractio.dev',
            'password' => Hash::make('password'),
            'role' => 'professeur',
        ]);

        // Create the Student user
        User::factory()->create([
            'name' => 'Etudiant Test',
            'email' => 'etudiant@insuractio.dev',
            'password' => Hash::make('password'),
            'role' => 'etudiant',
        ]);
    }
}
