<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('UPDATE data SET aforo = CAST(aforo AS CHAR)');

        // Modificar la columna a VARCHAR
        Schema::table('data', function (Blueprint $table) {
            $table->string('aforo', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE data SET aforo = CAST(aforo AS UNSIGNED)');

        Schema::table('data', function (Blueprint $table) {
            $table->integer('aforo')->change();
        });
    }
};
