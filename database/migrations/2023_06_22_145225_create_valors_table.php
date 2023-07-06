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
        Schema::create('valors', function (Blueprint $table) {
            $table->id();
            $table->string('valor');
            $table->foreignId('dt_campo_valor_id')->constrained('dt_campo_valors');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valors');
    }
};
