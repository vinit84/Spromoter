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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('store_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('unique_id')->nullable();
            $table->string('name')->nullable();

            $table->string('price')->nullable();
            $table->json('specs')->nullable();

            $table->string('image')->nullable();
            $table->string('url')->nullable();

            $table->longText('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unique(['store_id', 'unique_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
