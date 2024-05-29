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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('store_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('unique_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->integer('rating')->nullable();
            $table->json('attachments')->nullable();

            $table->string('source')->nullable();

            $table->string('collect_from')->default('unknown');
            $table->string('agent')->nullable();
            $table->json('device')->nullable();
            $table->json('location')->nullable();

            $table->boolean('is_verified')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_purchased')->default(0);
            $table->string('status')->default('pending')->comment('pending, published, rejected');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
