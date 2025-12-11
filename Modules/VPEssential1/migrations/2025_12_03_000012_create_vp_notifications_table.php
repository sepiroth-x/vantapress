<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Notifications System Migration
 * 
 * Creates table for user notifications
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('type', [
                'friend_request',
                'friend_accepted',
                'follower',
                'poke',
                'message',
                'post_like',
                'post_comment',
                'post_share',
                'tweet_like',
                'tweet_reply',
                'tweet_retweet',
                'comment_like',
                'comment_reply',
                'mention'
            ]);
            $table->morphs('notifiable'); // notifiable_id, notifiable_type (the related post, tweet, etc.)
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read', 'created_at'], 'vp_notif_user_idx');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_notifications');
    }
};
