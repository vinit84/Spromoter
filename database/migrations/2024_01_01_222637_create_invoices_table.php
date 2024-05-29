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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('subscription')->nullable();
            $table->string('number')->nullable();
            $table->string('stripe_id')->nullable();
            $table->string('currency')->nullable();
            $table->string('customer')->nullable();
            $table->string('hosted_invoice_url')->nullable();
            $table->string('invoice_pdf')->nullable();
            $table->decimal('amount', 20)->nullable();
            $table->string('status')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
