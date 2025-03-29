<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
        {
            Schema::create('kasbon_harian_marketing', function (Blueprint $table) {
                $table->id(); 
                $table->foreignId('marketing_id')->constrained('users'); 
                $table->bigInteger('nominal')->unsigned(); 
                $table->date('tanggal'); 
                $table->bigInteger('sisa_kasbon')->nullable(); 
                $table->boolean('status')->default(false);
                $table->boolean('is_locked')->default(false);
                $table->timestamps(); 
            });
        }

    public function down(): void
    {
        Schema::dropIfExists('kasbon_harian_marketing');
    }
};
