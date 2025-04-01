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
        Schema::create('validasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('users');
            $table->integer('pencairan')->unsigned(); 
            $table->integer('angsuran')->unsigned(); 
            $table->integer('tarik_simpanan')->unsigned(); 
            $table->integer('setor_simpanan')->unsigned(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi');
    }
};
