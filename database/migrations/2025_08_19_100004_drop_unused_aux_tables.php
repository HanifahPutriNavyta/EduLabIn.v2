<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('logins')) {
            Schema::drop('logins');
        }
        if (Schema::hasTable('registrasis')) {
            Schema::drop('registrasis');
        }
        // sessions table is used by database driver; don't drop unless driver is changed
        if (env('SESSION_DRIVER', 'database') !== 'database' && Schema::hasTable('sessions')) {
            Schema::drop('sessions');
        }
    }

    public function down()
    {
        // Can't easily recreate without original schema; leave empty intentionally
    }
};
