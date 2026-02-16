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
        Schema::create('email_group_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_group_id')->constrained('email_groups')->onDelete('cascade');
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('type')->default('to'); // to, cc, bcc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_group_recipients');
    }
};
