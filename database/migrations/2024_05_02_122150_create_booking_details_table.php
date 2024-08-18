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
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('armada_id')->constrained('armadas')->onDelete('cascade');
            $table->foreignId('pengemudi_id')->constrained('pengemudis')->onDelete('cascade');
            $table->foreignId('kondektur_id')->constrained('kondekturs')->onDelete('cascade');
            $table->integer('is_out');
            $table->integer('is_in');
            $table->string('jemput');
            $table->decimal('biaya_jemput', 15)->nullable();
            $table->decimal('harga_std', 15)->nullable();
            $table->decimal('total_harga', 15)->nullable();
            $table->decimal('diskon', 15)->nullable();
            $table->decimal('total_sisa_uang_jalan', 15)->nullable();
            $table->decimal('total_pengeluaran', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
