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
        Schema::create('bitacora_campos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('tipo_campo_id')->constrained('tipos_campos');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('bitacora_valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('confirmacion_dt_id')->constrained('confirmacion_dts')->onDelete('cascade');
            $table->foreignId('bitacora_campo_id')->constrained('bitacora_campos')->onDelete('cascade');
            $table->text('valor')->nullable();
            $table->boolean('activo')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_valores');
        Schema::dropIfExists('bitacora_campos');
    }
};
