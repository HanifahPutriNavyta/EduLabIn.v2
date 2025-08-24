<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftaran_aspraks', function (Blueprint $table) {
            $table->id('pendaftaran_id');
            $table->foreignId('mk_id')->constrained('mata_kuliahs', 'mk_id')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('kuota');
            $table->text('ketentuan')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_aspraks');
    }
}; 