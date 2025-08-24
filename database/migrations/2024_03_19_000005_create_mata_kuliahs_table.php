<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id('mk_id');
            $table->string('nama_mk', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mata_kuliahs');
    }
}; 