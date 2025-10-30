<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_visitas', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('id_data');
            $table->foreign('id_data')->references('id')->on('data')->onDelete('cascade');

            $table->unsignedBigInteger('id_servicio');
            $table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');

            $table->unsignedTinyInteger('descuento')->default(0);
        
            $table->decimal('subtotal', 10, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_visitas');
    }
};
