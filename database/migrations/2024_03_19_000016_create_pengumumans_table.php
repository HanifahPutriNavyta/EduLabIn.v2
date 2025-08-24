<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id('pengumuman_id');
            $table->foreignId('created_by')->constrained('users', 'user_id')->onDelete('restrict')->onUpdate('cascade');
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengumumans');
    }
}; 