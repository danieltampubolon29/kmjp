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
            $table->foreignId('pencairan_id')->constrained('pencairan')->nullable(); 
            $table->foreignId('marketing_id')->constrained('users');
            $table->date('tanggal_transaksi');
            $table->string('jenis_transaksi')->default('SETOR');
            $table->string('jenis_simpanan')->default('POKOK'); 
            $table->integer('nominal')->unsigned(); 
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
