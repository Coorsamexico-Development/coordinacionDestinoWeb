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
        Schema::create('dt_campo_valors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dt_id')->constrained('dts');
            $table->foreignId('campo_id')->constrained('campos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dt_campo_valors');
    }
};
