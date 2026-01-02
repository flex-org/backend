<?php

use App\Modules\Plans\Models\Plan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Modules\Platforms\Models\Platform;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained()->cascadeOnDelete();
            $table->date('started_at');
            $table->date('renew_at');
            $table->tinyInteger('duration_months')->default(1);
            $table->decimal('price', 10, 2);
            $table->string('status')->default('free_trial'); // free_trial, active, pending, expired, deactivated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
