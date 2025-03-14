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

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => 'daniel123',
        ]);
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role' => 'marketing',
            'password' => 'daniel123',
        ]);
        User::factory()->create([
            'name' => 'Fernan Dev',
            'email' => 'fernandev@gmail.com',
            'role' => 'marketing',
            'password' => 'daniel123',
        ]);
        $this->call(AnggotaSeeder::class);
        // $this->call(PencairanSeeder::class);
    }
}
