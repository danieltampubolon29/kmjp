<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pencairan_id')->nullable(); 
            $table->integer('angsuran_ke'); 
            $table->string('jenis_transaksi');
            $table->integer('nominal')->unsigned(); 
            $table->date('tanggal_angsuran'); 
            $table->foreignId('marketing_id')->constrained('users');
            $table->boolean('is_locked')->default(false);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
