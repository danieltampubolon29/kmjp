<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AngsuranSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Ambil semua data pencairan yang tersedia
        $pencairans = DB::table('pencairan')->get();

        if ($pencairans->isEmpty()) {
            return; // Jika tidak ada data pencairan, hentikan proses seeding
        }

        foreach ($pencairans as $pencairan) {
            // Hitung jumlah angsuran berdasarkan tenor
            $tenor = $pencairan->tenor;
            $totalNominal = $pencairan->nominal;
            $angsuranNominal = ceil($totalNominal / $tenor); // Nominal per angsuran

            for ($i = 1; $i <= $tenor; $i++) {
                // Tentukan tanggal angsuran secara acak tetapi logis
                $tanggalAngsuran = $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d');

                // Insert data angsuran
                DB::table('angsuran')->insert([
                    'pencairan_id' => $pencairan->id, // Hubungkan ke pencairan
                    'angsuran_ke' => $i, // Angsuran ke-berapa
                    'jenis_transaksi' => 'angsuran', // Jenis transaksi
                    'nominal' => $angsuranNominal, // Nominal angsuran
                    'tanggal_angsuran' => $tanggalAngsuran, // Tanggal angsuran
                    'marketing_id' => $pencairan->marketing_id, // Pastikan marketing_id sama dengan pencairan
                    'is_locked' => $faker->boolean(20), // Lock status secara acak
                    'tanggal_laporan' => null, // Opsional
                    'latitude' => $faker->optional(0.8)->latitude(-90, 90), // Opsional
                    'longitude' => $faker->optional(0.8)->longitude(-180, 180), // Opsional
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}