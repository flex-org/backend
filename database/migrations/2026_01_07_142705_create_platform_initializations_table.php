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
        Schema::create('platform_initializations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('domain')->nullable();
            $table->integer('capacity')->default(100);
            $table->integer('storage')->default(50);
            $table->json('selling_systems')->default([])->nullable();
            $table->boolean('mobile_app')->default(false);
            $table->integer('step')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_initializations');
    }
};
