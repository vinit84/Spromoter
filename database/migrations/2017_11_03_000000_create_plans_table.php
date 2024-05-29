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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('stripe_id')->unique();

            $table->float('monthly_price')->nullable();
            $table->float('yearly_price')->nullable();

            $table->string('monthly_price_id')->nullable();
            $table->string('yearly_price_id')->nullable();

            $table->integer('monthly_order')->nullable();

            $table->integer('trial_days')->default(0);

            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->json('card_features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_from')->default('web');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
