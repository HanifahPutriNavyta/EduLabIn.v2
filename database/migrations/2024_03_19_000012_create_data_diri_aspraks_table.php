<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_diri_aspraks', function (Blueprint $table) {
            $table->id('dataDiri_id');
            $table->foreignId('asprak_id')->constrained('aspraks', 'asprak_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kelas_id')->constrained('kelas_praktikums', 'kelas_id')->onDelete('restrict')->onUpdate('cascade');
            $table->string('nama', 100)->nullable();
            $table->string('nim', 20)->nullable();
            $table->string('nomor_ktp', 20)->nullable();
            $table->string('nomor_whatsapp', 20)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->integer('jumlah_mahasiswa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_diri_aspraks');
    }
}; 