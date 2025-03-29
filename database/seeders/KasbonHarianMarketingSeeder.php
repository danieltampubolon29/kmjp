<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KasbonHarianMarketingSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 105; $i++) { 
            DB::table('kasbon_harian_marketing')->insert([
                'marketing_id' => rand(2, 3), 
                'nominal' => rand(500000, 5000000), 
                'tanggal' => Carbon::now()->subDays(rand(1, 30))->toDateString(), 
                'sisa_kasbon' => 0 , 
                'status' => 0 ,
                'is_locked' => 0 , 
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
