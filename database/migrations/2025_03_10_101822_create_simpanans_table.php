<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota'); 
            $table->foreignId('pencairan_id')->nullable(); 
            $table->foreignId('marketing_id')->constrained('users');
            $table->date('tanggal_transaksi');
            $table->string('jenis_transaksi');
            $table->string('jenis_simpanan'); 
            $table->integer('nominal')->unsigned(); 
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
