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
        Schema::create('armadas', function (Blueprint $table) {
            $table->id();
            $table->string('nobody')->unique();
            $table->string('nochassis')->nullable();
            $table->string('nomesin')->nullable();
            $table->string('nopolisi')->nullable();
            $table->foreignId('rute_id')->constrained('rutes')->onDelete('cascade');
            $table->string('merk')->nullable();
            $table->string('tahun')->nullable();
            $table->string('jenis')->nullable();
            $table->string('seat')->nullable();
            $table->foreignId('type_id')->constrained('type_armadas')->onDelete('cascade');
            $table->string('kondisi')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('bis');
    }
};
