<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kelas_praktikums', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_praktikums', 'tahun_ajaran')) {
                $table->dropColumn('tahun_ajaran');
            }
            if (Schema::hasColumn('kelas_praktikums', 'semester')) {
                $table->dropColumn('semester');
            }
            // Note: dropping status only if confirmed; keep for now to avoid breaking filters
            // if (Schema::hasColumn('kelas_praktikums', 'status')) {
            //     $table->dropColumn('status');
            // }
        });
    }

    public function down()
    {
        Schema::table('kelas_praktikums', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas_praktikums', 'tahun_ajaran')) {
                $table->string('tahun_ajaran', 20)->nullable();
            }
            if (!Schema::hasColumn('kelas_praktikums', 'semester')) {
                $table->boolean('semester')->nullable();
            }
            // if (!Schema::hasColumn('kelas_praktikums', 'status')) {
            //     $table->boolean('status')->default(true);
            // }
        });
    }
};
