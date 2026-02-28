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
            $table->string('domain')->unique();
            $table->date('started_at')->nullable();
            $table->date('renew_at')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('status')->default('free_trial'); // free_trial, active, pending, expired, deactivated, draft
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
