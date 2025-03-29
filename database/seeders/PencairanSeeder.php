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

        // Ambil daftar anggota dan marketing yang tersedia
        $anggotas = Anggota::all(['id', 'no_anggota', 'nama'])->toArray();
        $users = User::where('role', 'marketing')->pluck('id')->toArray();

        if (empty($anggotas) || empty($users)) {
            return;
        }

        for ($i = 1; $i <= 100; $i++) { 
            // Pilih anggota secara acak
            $anggota = $faker->randomElement($anggotas);
            $marketingId = $faker->randomElement($users);

            // Ambil pinjaman terakhir untuk menentukan pinjaman_ke
            $lastPinjaman = Pencairan::where('anggota_id', $anggota['id'])
                ->orderBy('pinjaman_ke', 'desc')
                ->first();

            $pinjamanKe = $lastPinjaman ? $lastPinjaman->pinjaman_ke + 1 : 1;

            // Tentukan nominal dan sisa kredit
            $nominal = $faker->numberBetween(500000, 5000000);
            $sisa_kredit = (int) ($nominal * 1.2);

            DB::table('pencairan')->insert([
                'anggota_id' => $anggota['id'],
                'no_anggota' => $anggota['no_anggota'],
                'nama' => $anggota['nama'],
                'pinjaman_ke' => $pinjamanKe,
                'produk' => $faker->randomElement(['Harian', 'Mingguan']), 
                'nominal' => $nominal,
                'tenor' => $faker->numberBetween(1, 52), 
                'jatuh_tempo' => $faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Harian']),
                'sisa_kredit' => $sisa_kredit,
                'tanggal_pencairan' => now()->format('Y-m-d'),
                'foto_pencairan' => null, 
                'foto_rumah' => null, 
                'marketing' => User::find($marketingId)->name ?? 'Unknown', // Ambil nama marketing
                'marketing_id' => $marketingId,
                'status' => false, // Sesuai migration
                'is_locked' => false, // Default value sesuai migration
                'latitude' => (string) $faker->latitude(-90, 90), 
                'longitude' => (string) $faker->longitude(-180, 180), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
