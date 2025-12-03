<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - User Profiles Migration
 * 
 * Creates table for extended user profiles
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('display_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('github')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('location')->nullable();
            $table->json('social_links')->nullable(); // Additional social links
            $table->json('settings')->nullable(); // User preferences
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_user_profiles');
    }
};
