<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armadasubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armada_id')->constrained()->onDelete('cascade');
            $table->string('imeigps')->unique();
            $table->string('nomor_kir')->unique();
            $table->string('kodetrayek')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->date('awal_stnk')->nullable();
            $table->date('akhir_stnk')->nullable();
            $table->date('kir_habis')->nullable();
            $table->date('kps_habis')->nullable();
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('armadasubs');
    }
};
