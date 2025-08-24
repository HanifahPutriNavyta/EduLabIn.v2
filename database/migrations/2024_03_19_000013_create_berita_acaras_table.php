<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id('berita_id');
            $table->foreignId('asprak_id')->constrained('aspraks', 'asprak_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kelas_id')->constrained('kelas_praktikums', 'kelas_id')->onDelete('restrict')->onUpdate('cascade');
            $table->date('tanggal_kegiatan');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->string('judul')->nullable();
            $table->enum('tipe_pertemuan', ['daring', 'luring'])->default('luring');
            $table->string('upload_berita_acara', 255)->nullable();
            $table->string('upload_bukti_pertemuan', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->boolean('status')->default(false); // false for pending, true for approved
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('berita_acaras');
    }
}; 