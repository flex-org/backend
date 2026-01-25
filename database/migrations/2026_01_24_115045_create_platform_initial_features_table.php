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
        Schema::create('platform_initial_features', function (Blueprint $table) {
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->foreignId('platform_id')->constrained('platform_initializations')->cascadeOnDelete();
            $table->unique(['feature_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_initial_features');
    }
};
