<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profil_penggunas', function (Blueprint $table) {
            $table->id('profile_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('no_identitas', 50)->nullable();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('fakultas', 100)->nullable();
            $table->string('departemen', 100)->nullable();
            $table->string('program_studi', 100)->nullable();
            $table->string('status_akademik', 50)->nullable();
            $table->string('no_whatsapp', 20)->nullable();
            $table->string('foto_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profil_penggunas');
    }
}; 