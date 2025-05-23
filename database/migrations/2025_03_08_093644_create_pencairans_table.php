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
        Schema::create('pencairan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->integer('no_anggota');
            $table->string('nama');
            $table->integer('pinjaman_ke');
            $table->string('produk');
            $table->integer('nominal')->unsigned();
            $table->integer('tenor');
            $table->string('jatuh_tempo');
            $table->integer('sisa_kredit');
            $table->date('tanggal_pencairan');
            $table->date('tanggal_laporan')->nullable();
            $table->string('foto_pencairan')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->string('marketing');
            $table->foreignId('marketing_id')->constrained('users');
            $table->boolean('status')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
