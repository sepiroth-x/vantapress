<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Comments System Migration
 * 
 * Creates tables for comments on posts and tweets
 */
return new class extends Migration
{
    public function up(): void
    {
        // Comments table (polymorphic - works for posts, tweets, etc.)
        Schema::create('vp_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->morphs('commentable'); // commentable_id, commentable_type
            $table->foreignId('parent_id')->nullable()->constrained('vp_comments')->onDelete('cascade');
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['commentable_type', 'commentable_id', 'created_at'], 'vp_comments_poly_idx');
            $table->index('user_id');
            $table->index('parent_id');
        });
        
        // Comment likes table
        Schema::create('vp_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('vp_comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['comment_id', 'user_id'], 'vp_comment_likes_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_comment_likes');
        Schema::dropIfExists('vp_comments');
    }
};
