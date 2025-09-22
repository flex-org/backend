<?php

use App\Modules\Plans\Models\Plan;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->string('type');
            $table->smallInteger('storage')->nullable();
            $table->Integer('capacity')->nullable();
            $table->timestamps();
        });

        Schema::create('plan_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Plan::class)->constrained()->cascadeOnDelete();
            $table->string('billing_cycle'); // monthly, yearly
            $table->tinyInteger('months')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->boolean('is_in_sale')->default(false);
            $table->timestamps();
        });

        Schema::create('plan_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Plan::class)->constrained()->cascadeOnDelete();
            $table->char('locale',3)->index();
            $table->text('description')->nullable();
            $table->json('points')->nullable();
            $table->unique(['plan_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_prices');
        Schema::dropIfExists('plan_translations');
        Schema::dropIfExists('plans');
    }
};
