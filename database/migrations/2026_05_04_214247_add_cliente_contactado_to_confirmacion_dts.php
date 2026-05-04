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
        Schema::table('confirmacion_dts', function (Blueprint $table) {
            $table->boolean('cliente_contactado')->default(false)->after('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirmacion_dts', function (Blueprint $table) {
            $table->dropColumn('cliente_contactado');
        });
    }
};
