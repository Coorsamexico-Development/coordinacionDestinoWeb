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
        Schema::create('confirmacion_dts', function (Blueprint $table) {
            $table->id();
            $table->string('confirmacion');
            $table->foreignId('dt_id')->constrained('dts');
            $table->dateTime('cita');
            $table->bigInteger('numero_cajas');
            $table->foreignId('status_id')->constrained('status');
            $table->foreignId('linea_transporte_id')->constrained('linea_transportes');
            $table->foreignId('plataforma_id')->constrained('plataformas');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('ubicacion_id')->constrained('ubicaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmacion_dts');
    }
};
