<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('anggota', function (Blueprint $table) {
        $table->id();
        $table->integer('no_anggota')->unique();
        $table->string('nama');
        $table->date('tanggal_lahir');
        $table->text('alamat_ktp');
        $table->text('alamat_domisili');
        $table->string('no_hp')->nullable();
        $table->date('tanggal_daftar');
        $table->foreignId('marketing_id')->constrained('users'); 
        $table->boolean('is_locked')->default(false);
        $table->string('foto_ktp')->nullable(); 
        $table->string('foto_kk')->nullable();
        $table->string('latitude'); 
        $table->string('longitude');
        $table->timestamps();
    });
}

    
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
