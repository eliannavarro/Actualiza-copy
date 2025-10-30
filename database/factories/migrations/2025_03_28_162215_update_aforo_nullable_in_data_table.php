<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Convertir aforo a VARCHAR(255)
        Schema::table('data', function (Blueprint $table) {
            $table->string('aforo', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Reemplazar valores no numéricos por NULL antes de convertir a INTEGER
        DB::statement("UPDATE data SET aforo = NULL WHERE aforo NOT REGEXP '^[0-9]+$'");

        Schema::table('data', function (Blueprint $table) {
            $table->integer('aforo')->nullable()->change();
        });
    }
};