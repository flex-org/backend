<?php

use App\Models\User;
use App\Modules\Plans\Models\Plan;
use function Laravel\Prompts\table;
use App\Modules\Themes\Models\Theme;
use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained()->cascadeOnDelete();
            $table->string('domain')->unique();
            $table->integer('storage');
            $table->integer('capacity');
            $table->boolean('has_mobile_app')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
