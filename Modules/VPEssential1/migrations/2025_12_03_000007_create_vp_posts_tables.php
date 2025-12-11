<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Posts System Migration
 * 
 * Creates tables for posts (Facebook-style status updates)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Posts table
        Schema::create('vp_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->enum('type', ['status', 'photo', 'video', 'link', 'shared'])->default('status');
            $table->json('media')->nullable(); // Array of image/video URLs
            $table->string('link_url')->nullable();
            $table->string('link_title')->nullable();
            $table->text('link_description')->nullable();
            $table->string('link_image')->nullable();
            $table->foreignId('shared_post_id')->nullable()->constrained('vp_posts')->onDelete('cascade');
            $table->enum('visibility', ['public', 'friends', 'private'])->default('public');
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['is_published', 'visibility']);
            $table->index('created_at');
        });
        
        // Post shares table
        Schema::create('vp_post_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('vp_posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->index(['post_id', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_post_shares');
        Schema::dropIfExists('vp_posts');
    }
};
