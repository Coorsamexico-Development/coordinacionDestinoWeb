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
        Schema::create('horas_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hora_id')->constrained('horas');
            $table->foreignId('status_dts_id')->constrained('status_dts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horas_historicos');
    }
};
