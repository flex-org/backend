<?php

use Illuminate\Support\Facades\Schema;
use App\Modules\Platforms\Models\Platform;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Modules\Themes\Models\Theme;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platform_theme', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Platform::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Theme::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_theme');
    }
};
