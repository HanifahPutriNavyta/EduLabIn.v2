<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('mata_kuliahs', 'status_pendaftaran')) {
            Schema::table('mata_kuliahs', function (Blueprint $table) {
                $table->dropColumn('status_pendaftaran');
            });
        }
    }

    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_kuliahs', 'status_pendaftaran')) {
                $table->boolean('status_pendaftaran')->nullable()->after('nama_mk');
            }
        });
    }
};
