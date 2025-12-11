<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Connections (Friends/Followers) Migration
 * 
 * Creates tables for friend requests, followers, and pokes
 */
return new class extends Migration
{
    public function up(): void
    {
        // Friend connections (mutual)
        Schema::create('vp_friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['friend_id', 'status']);
            $table->unique(['user_id', 'friend_id'], 'vp_friends_unique');
        });
        
        // Followers (one-way)
        Schema::create('vp_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'follower_id'], 'vp_followers_unique');
            $table->index('user_id');
            $table->index('follower_id');
        });
        
        // Pokes
        Schema::create('vp_pokes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['to_user_id', 'is_read']);
            $table->index('from_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_pokes');
        Schema::dropIfExists('vp_followers');
        Schema::dropIfExists('vp_friends');
    }
};
