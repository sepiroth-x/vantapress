<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Verification System Migration
 * 
 * Creates table for verified users (blue checkmark)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->boolean('is_verified')->default(false);
            $table->enum('badge_type', ['blue', 'gold', 'gray'])->default('blue'); // Different verification types
            $table->string('verified_by')->nullable(); // Admin who verified
            $table->text('reason')->nullable(); // Why they were verified
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_verifications');
    }
};
