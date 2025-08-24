<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('absensi_praktikans', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->foreign('kelas_id')
                ->references('kelas_id')->on('kelas_praktikums')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_praktikans', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->foreign('kelas_id')
                ->references('kelas_id')->on('kelas_praktikums')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }
};
