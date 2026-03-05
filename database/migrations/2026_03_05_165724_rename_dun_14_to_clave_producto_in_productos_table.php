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
        Schema::table('productos', function (Blueprint $table) {
            $table->renameColumn('DUN 14', 'clave_producto');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->string('clave_producto')->unique()->after('id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique(['clave_producto']);
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->renameColumn('clave_producto', 'DUN 14');
        });
    }
};
