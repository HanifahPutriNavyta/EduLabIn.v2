<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi_praktikans', function (Blueprint $table) {
            $table->id('absensi_id');
            $table->foreignId('kelas_id')->constrained('kelas_praktikums', 'kelas_id')->onDelete('restrict')->onUpdate('cascade');
            $table->string('judul', 255);
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->string('upload_file', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_praktikans');
    }
}; 