<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            // place after nama_mk for readability
            // Using a more explicit column name to avoid ambiguity with other "status" fields
            $table->boolean('status_pendaftaran')->default(true)->after('nama_mk');
        });
    }

    public function down()
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn('status_pendaftaran');
        });
    }
};
