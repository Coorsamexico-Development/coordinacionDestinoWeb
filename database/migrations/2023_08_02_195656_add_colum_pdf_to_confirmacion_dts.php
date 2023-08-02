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
            //
            $table->string('pdf')->nullable()->before('ubicacion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confirmacion_dts', function (Blueprint $table) {
            //
            $table->dropColumn('pdf');
        });
    }
};
