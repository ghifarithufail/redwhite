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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('noinduk')->unique();
            $table->foreignId('jabatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('pool_id')->constrained('pools')->onDelete('cascade');
            $table->date('tanggal_kp');
            $table->date('tgl_masuk');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
