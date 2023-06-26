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
        Schema::create('status_dts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('confirmacion_dt_id')->constrained('confirmacion_dts');
            $table->foreignId('status_id')->constrained('status');
            $table->boolean('activo')->default((1));
            $table->timestamps();
        });

        Schema::table('status_dts', function($table) {
            $table->dropColumn(['hora']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_dts');
    }
};
