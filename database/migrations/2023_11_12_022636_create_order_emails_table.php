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
        Schema::create('order_emails', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();

            $table->string('status')->default('pending');

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('limit_exceeded_at')->nullable();

            $table->text('failed_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_emails');
    }
};
