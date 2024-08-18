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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('no_booking');
            $table->string('customer');
            $table->bigInteger('telephone');
            $table->decimal('harga_std');
            $table->string('total_payment')->default(0);
            $table->string('penalty_price')->default(0);
            $table->date('date_start');
            $table->date('date_end');
            $table->integer('total_bus');
            $table->foreignId('tujuan_id')->constrained('tujuans')->onDelete('cascade');
            $table->decimal('diskon');
            $table->decimal('biaya_jemput');
            $table->decimal('grand_total');
            $table->decimal('total_pengeluaran');
            $table->decimal('total_pendapatan');
            $table->integer('payment_status')->default(2);
            $table->integer('booking_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
