<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nilai_praktikums', function (Blueprint $table) {
            $table->id('nilai_id');
            $table->foreignId('kelas_id')->constrained('kelas_praktikums', 'kelas_id')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('asprak_id')->constrained('aspraks', 'asprak_id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('judul', 100);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->string('upload_file', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_praktikums');
    }
}; 