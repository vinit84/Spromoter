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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('order_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->string('currency')->nullable();

            $table->string('subtotal')->nullable();
            $table->string('subtotal_tax')->nullable();
            $table->string('total')->nullable();
            $table->string('total_tax')->nullable();
            $table->json('taxes')->nullable();

            $table->string('platform')->nullable();
            $table->string('status')->nullable();
            $table->longText('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
