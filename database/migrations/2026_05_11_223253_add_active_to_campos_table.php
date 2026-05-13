<?php

use App\Models\Campo;
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
        Schema::table('campos', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('with_evidencias');
        });
        Campo::where('nombre', 'FACTURA')
        ->where('status_id', 6)
        ->update(['active' => false]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campos', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
