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
        Schema::create('confirmacion_fechas_pods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('confirmacion_dt_id')->constrained('confirmacion_dts');
            $table->foreignId('fecha_pod_id')->constrained('fechas_pods');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmacion_fechas_pods');
    }
};
