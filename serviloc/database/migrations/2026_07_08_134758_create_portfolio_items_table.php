<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();   // e.g. "Plumbing", "Electrical"
            $table->string('location')->nullable();
            $table->decimal('price_from', 10, 2)->nullable(); // starting price for this type of job
            $table->string('image_path')->nullable();         // uploaded image
            $table->integer('duration_days')->nullable();     // how long the job took
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('provider_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};
