<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hari_kerja', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique(); 
            $table->boolean('status')->default(false);
            $table->string('description')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workdays');
    }
};