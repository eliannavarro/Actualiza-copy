<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('orden')->unique()->nullable();
            $table->string('nombres')->nullable();
            $table->string('cuentaContrato')->nullable();
            $table->string('direccion')->nullable();
            $table->string('causanl_obs')->nullable();
            $table->string('obs_adic')->nullable();

            $table->string('medidor')->nullable();
            $table->integer('lectura')->nullable();
            $table->integer('aforo')->nullable();
            $table->string('resultado')->nullable();
            $table->string('observacion_inspeccion')->nullable();
            $table->string('url_foto')->nullable();
            $table->text('firmaUsuario')->nullable();
            $table->text('firmaTecnico')->nullable();
            $table->string('ciclo')->nullable();
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->integer('puntoHidraulico')->nullable();
            $table->integer('numeroPersonas')->nullable();
            $table->string('categoria')->nullable();
            $table->string('estado')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
