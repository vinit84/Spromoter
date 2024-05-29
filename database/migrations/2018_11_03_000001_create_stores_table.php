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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('store_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_integrated')->default(0);

            $table->integer('total_orders')->default(0);
            $table->integer('used_orders')->default(0);

            $table->string('preview_image')->nullable();
            $table->timestamp('preview_image_updated_at')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
