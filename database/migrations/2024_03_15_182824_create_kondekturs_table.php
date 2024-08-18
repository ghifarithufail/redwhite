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
        Schema::create('kondekturs', function (Blueprint $table) {
            $table->id();
            $table->string('nokondektur')->unique();
            $table->foreignId('rute_id')->constrained()->onDelete('cascade');
            $table->foreignId('pool_id')->constrained('pools')->onDelete('cascade');
            $table->date('tgl_masuk');
            $table->date('tanggal_kp');
            $table->string('nojamsostek')->nullable(false)->change();
            $table->string('status')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ket_kondektur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondekturs');
    }
};
