<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCalonAspraksFkMkId extends Migration
{
    /**
     * Run the migrations.
     * Drop existing foreign key and re-create it with ON DELETE CASCADE.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_aspraks', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            if (Schema::hasColumn('calon_aspraks', 'mk_id')) {
                // try both ways to drop: named and column-based
                try {
                    $table->dropForeign(['mk_id']);
                } catch (\Exception $e) {
                    // ignore: constraint might have a different name or already removed
                }

                // Re-create foreign key with cascade on delete
                $table->foreign('mk_id')
                    ->references('mk_id')
                    ->on('mata_kuliahs')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Restore original behavior (RESTRICT) to be safe.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calon_aspraks', function (Blueprint $table) {
            if (Schema::hasColumn('calon_aspraks', 'mk_id')) {
                try {
                    $table->dropForeign(['mk_id']);
                } catch (\Exception $e) {
                    // ignore
                }

                // Re-create foreign key with restrict on delete (original behavior)
                $table->foreign('mk_id')
                    ->references('mk_id')
                    ->on('mata_kuliahs')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
            }
        });
    }
}
