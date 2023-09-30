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
        Schema::table('dt_campo_valors', function (Blueprint $table) {
            //
            $table->dropForeign(['dt_id']);
            $table->dropColumn('dt_id');
            $table->foreignId('confirmacion_id')->constrained('confirmacion_dts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dt_campo_valors', function (Blueprint $table) {
            //
        });
    }
};
