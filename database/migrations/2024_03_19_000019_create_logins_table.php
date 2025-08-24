<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logins', function (Blueprint $table) {
            $table->id('login_id');
            $table->string('username', 50);
            $table->string('password', 255);
            $table->boolean('role');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logins');
    }
}; 