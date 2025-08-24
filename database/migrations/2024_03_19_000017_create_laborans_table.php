<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laborans', function (Blueprint $table) {
            $table->id('laboran_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama', 100);
            $table->string('nip', 20)->unique();
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laborans');
    }
}; 