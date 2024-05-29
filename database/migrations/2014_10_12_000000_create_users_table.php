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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // (first_name + last_name)
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('phone_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('group')->default('customer');

            $table->string('profile_photo_url')->nullable();

            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('about')->nullable();

            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_skype')->nullable();

            $table->string('oauth_provider')->nullable();
            $table->string('oauth_provider_id')->nullable();

            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();
            $table->string('timezone')->nullable();
            $table->string('status')->default('active');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
