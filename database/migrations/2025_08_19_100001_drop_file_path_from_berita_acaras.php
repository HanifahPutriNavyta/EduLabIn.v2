<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('berita_acaras', 'file_path')) {
            Schema::table('berita_acaras', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('berita_acaras', 'file_path')) {
            Schema::table('berita_acaras', function (Blueprint $table) {
                $table->string('file_path', 255)->nullable();
            });
        }
    }
};
