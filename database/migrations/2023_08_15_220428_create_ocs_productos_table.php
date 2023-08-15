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
        Schema::create('ocs_productos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->foreignId('tipo_incidencia_id')->constrained('tipo_incidencias');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('oc_id')->constrained('ocs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocs_productos');
    }
};
