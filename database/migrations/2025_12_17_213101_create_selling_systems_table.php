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
        Schema::create('selling_systems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('selling_system_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selling_system_id')->constrained()->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_system_translations');
        Schema::dropIfExists('selling_systems');
    }
};
