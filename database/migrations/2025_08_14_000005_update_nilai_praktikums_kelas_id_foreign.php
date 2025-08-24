<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nilai_praktikums', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->foreign('kelas_id')
                ->references('kelas_id')->on('kelas_praktikums')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
    public function down(): void
    {
        Schema::table('nilai_praktikums', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->foreign('kelas_id')
                ->references('kelas_id')->on('kelas_praktikums')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }
};
