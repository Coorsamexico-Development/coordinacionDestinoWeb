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
            $table->foreignId('origen_id')->nullable()->after('ubicacion_id')->constrained('ubicaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirmacion_dts', function (Blueprint $table) {
            $table->dropForeign(['origen_id']);
            $table->dropColumn('origen_id');
        });
    }
};
