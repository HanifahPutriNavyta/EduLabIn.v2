<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('session_logins', function (Blueprint $table) {
            $table->id('session_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('login_time')->useCurrent();
            $table->timestamp('logout_time')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('session_logins');
    }
}; 