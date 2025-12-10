<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Tweets Migration
 * 
 * Creates table for micro-blogging (tweeting) system
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_tweets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->integer('retweets_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->foreignId('reply_to_id')->nullable()->constrained('vp_tweets')->onDelete('cascade');
            $table->foreignId('retweet_of_id')->nullable()->constrained('vp_tweets')->onDelete('cascade');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'created_at']);
            $table->index('is_published');
        });
        
        // Tweet likes table
        Schema::create('vp_tweet_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tweet_id')->constrained('vp_tweets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['tweet_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_tweet_likes');
        Schema::dropIfExists('vp_tweets');
    }
};
