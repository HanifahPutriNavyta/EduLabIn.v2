<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('registrasis', function (Blueprint $table) {
            $table->id('registrasi_id');
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('password', 255);
            $table->string('confirm_password', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrasis');
    }
}; 