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
        Schema::create('biodatas', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nokk')->nullable();
            $table->foreignId('kota_id')->constrained('kotas')->onDelete('cascade');
            $table->date('tgl_lahir')->nullable();
            $table->string('nikah')->nullable();
            $table->string('agama')->nullable();
            $table->string('jenis')->nullable();
            $table->string('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->binary('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodatas');
    }
};
