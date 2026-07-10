<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('ETB');
            // pending → paid → released → refunded
            $table->enum('status', ['pending', 'paid', 'released', 'refunded'])->default('pending');
            $table->string('transaction_id')->unique()->nullable(); // generated on payment
            $table->string('payment_method')->default('simulated'); // future: telebirr, stripe, etc.
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('provider_id');
            $table->index('service_request_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
