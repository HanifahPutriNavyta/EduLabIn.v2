<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aspraks', function (Blueprint $table) {
            $table->id('asprak_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kelas_id')->constrained('kelas_praktikums', 'kelas_id')->onDelete('restrict')->onUpdate('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aspraks');
    }
}; 