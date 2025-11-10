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
            $table->string('nombre_auditor')->nullable();
            $table->string('direccion')->nullable();
            $table->string('causanl_obs')->nullable();
            $table->string('ciclo')->nullable();
            $table->string('obs_adic')->nullable();
            $table->string('medidor')->nullable();


            $table->string('lector')->nullable();
            $table->string('atendio_usuario')->nullable();
            $table->string('auditor')->nullable();
            $table->string('observacion_inspeccion')->nullable();
            $table->string('url_foto')->nullable();
            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
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
