<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calon_aspraks', function (Blueprint $table) {
            $table->id('calon_id');
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran_aspraks', 'pendaftaran_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mk_id')->constrained('mata_kuliahs', 'mk_id')->onDelete('restrict')->onUpdate('cascade');
            $table->date('tanggal_daftar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calon_aspraks');
    }
}; 