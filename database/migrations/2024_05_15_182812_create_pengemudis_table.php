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
        Schema::create('pengemudis', function (Blueprint $table) {
            $table->id();
            $table->string('nopengemudi')->unique();
            $table->foreignId('rute_id')->constrained('rutes')->onDelete('cascade');
            $table->foreignId('pool_id')->constrained('pools')->onDelete('cascade');
            $table->date('tgl_masuk');
            $table->date('tanggal_kp');
            $table->integer('nosim')->unique();
            $table->string('jenis_sim')->nullable();
            $table->date('tgl_sim')->nullable();
            $table->integer('nojamsostek')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->string('ket_pengemudi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengemudis');
    }
};
