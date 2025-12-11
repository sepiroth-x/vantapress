<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Hashtags System Migration
 * 
 * Creates tables for hashtags on posts and tweets
 */
return new class extends Migration
{
    public function up(): void
    {
        // Hashtags table
        Schema::create('vp_hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('usage_count')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->timestamps();
            
            $table->index(['usage_count', 'is_trending']);
            $table->index('name');
        });
        
        // Hashtaggables table (polymorphic - works for posts, tweets, etc.)
        Schema::create('vp_hashtaggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hashtag_id')->constrained('vp_hashtags')->onDelete('cascade');
            $table->morphs('hashtaggable'); // hashtaggable_id, hashtaggable_type
            $table->timestamps();
            
            $table->unique(['hashtag_id', 'hashtaggable_id', 'hashtaggable_type'], 'vp_hashtaggables_unique');
            $table->index(['hashtaggable_type', 'hashtaggable_id'], 'vp_hashtaggables_poly_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_hashtaggables');
        Schema::dropIfExists('vp_hashtags');
    }
};
