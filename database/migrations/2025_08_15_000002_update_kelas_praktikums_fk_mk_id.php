<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKelasPraktikumsFkMkId extends Migration
{
    /**
     * Run the migrations.
     * Drop existing foreign key and re-create it with ON DELETE CASCADE.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas_praktikums', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_praktikums', 'mk_id')) {
                try {
                    $table->dropForeign(['mk_id']);
                } catch (\Exception $e) {
                    // ignore if constraint name differs or doesn't exist
                }

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
        Schema::table('kelas_praktikums', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_praktikums', 'mk_id')) {
                try {
                    $table->dropForeign(['mk_id']);
                } catch (\Exception $e) {
                    // ignore
                }

                $table->foreign('mk_id')
                    ->references('mk_id')
                    ->on('mata_kuliahs')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
            }
        });
    }
}
