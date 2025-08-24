<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_calon_aspraks', function (Blueprint $table) {
            $table->id('calonAsprak_id');
            $table->foreignId('calon_id')->constrained('calon_aspraks', 'calon_id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama', 100);
            $table->string('nim', 20);
            $table->string('email', 100);
            $table->string('prodi', 100);
            $table->string('nomor_whatsapp', 20);
            $table->string('tahun_ajaran', 20);
            $table->string('bukti_file')->nullable();
            $table->string('foto_file')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_calon_aspraks');
    }
}; 