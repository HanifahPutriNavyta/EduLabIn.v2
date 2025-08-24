<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas_praktikums', function (Blueprint $table) {
            $table->id('kelas_id');
            $table->foreignId('mk_id')->constrained('mata_kuliahs', 'mk_id')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('dosen_id')->nullable()->constrained('dosens', 'dosen_id')->onDelete('restrict')->onUpdate('cascade');
            $table->string('kode_kelas', 20);
            $table->string('kode_enroll', 20)->unique();
            $table->string('tahun_ajaran', 20)->nullable();
            $table->boolean('semester')->nullable(); // true for ganjil, false for genap
            $table->boolean('status')->default(true); // true for active, false for inactive
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas_praktikums');
    }
}; 