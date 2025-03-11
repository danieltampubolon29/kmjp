<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Anggota;
use App\Models\User;
use App\Models\Pencairan;

class PencairanSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); 

        $anggotas = Anggota::pluck('id')->toArray();

        $users = User::where('role', 'marketing')->pluck('id')->toArray();

        if (empty($anggotas) || empty($users)) {
            return;
        }

        for ($i = 1; $i <= 50; $i++) { 
            $anggotaId = $faker->randomElement($anggotas); 
            $marketingId = $faker->randomElement($users); 

            $lastPinjaman = Pencairan::where('anggota_id', $anggotaId)
                ->orderBy('pinjaman_ke', 'desc')
                ->first();

            $pinjamanKe = $lastPinjaman ? $lastPinjaman->pinjaman_ke + 1 : 1;

            $nominal = $faker->numberBetween(500000, 5000000); 

            DB::table('pencairan')->insert([
                'anggota_id' => $anggotaId,
                'pinjaman_ke' => $pinjamanKe,
                'produk' => $faker->randomElement(['Harian', 'Mingguan']), 
                'nominal' => $nominal,
                'tenor' => $faker->numberBetween(1, 52), 
                'jatuh_tempo' => $faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Harian']),
                'tanggal_pencairan' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), 
                'foto_pencairan' => null, 
                'foto_rumah' => null, 
                'marketing' => $faker->randomElement(['Hitler', 'Jubrito', 'Hendri']),
                'marketing_id' => $marketingId,
                'is_locked' => $faker->boolean(), 
                'latitude' => $faker->latitude(-90, 90), 
                'longitude' => $faker->longitude(-180, 180), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}