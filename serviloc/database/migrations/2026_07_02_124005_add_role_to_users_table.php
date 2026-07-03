<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'provider', 'admin'])->default('customer');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'profile_photo', 'bio', 'is_verified']);
        });
    }
};