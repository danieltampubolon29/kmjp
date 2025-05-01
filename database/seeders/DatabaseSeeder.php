<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
        $this->call([
            AnggotaSeeder::class,
            // PencairanSeeder::class,
            // AngsuranSeeder::class,
            // KasbonHarianMarketingSeeder::class,
        ]);
    }
}
