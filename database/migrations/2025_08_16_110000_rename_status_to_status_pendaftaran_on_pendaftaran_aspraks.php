<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pendaftaran_aspraks', 'status') && !Schema::hasColumn('pendaftaran_aspraks', 'status_pendaftaran')) {
            Schema::table('pendaftaran_aspraks', function (Blueprint $table) {
                $table->renameColumn('status', 'status_pendaftaran');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pendaftaran_aspraks', 'status_pendaftaran') && !Schema::hasColumn('pendaftaran_aspraks', 'status')) {
            Schema::table('pendaftaran_aspraks', function (Blueprint $table) {
                $table->renameColumn('status_pendaftaran', 'status');
            });
        }
    }
};
