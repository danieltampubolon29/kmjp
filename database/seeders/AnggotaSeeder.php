<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); 

        for ($i = 1; $i <= 15; $i++) { 
            DB::table('anggota')->insert([
                'no_anggota' => 00 + $i, 
                'nama' => $faker->name(),
                'tanggal_lahir' => $faker->date(),
                'alamat_ktp' => $faker->address(),
                'alamat_domisili' => $faker->address(),
                'no_hp' => $faker->phoneNumber(),
                'tanggal_daftar' => $faker->date(),
                'marketing_id' => rand(2,3), 
                'is_locked' => $faker->boolean(),
                'foto_ktp' => null,
                'foto_kk' => null,
                'latitude' => $faker->latitude(),
                'longitude' => $faker->longitude(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
