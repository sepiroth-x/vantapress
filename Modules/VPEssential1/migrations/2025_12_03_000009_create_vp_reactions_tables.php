<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Reactions System Migration
 * 
 * Creates tables for Facebook-style reactions (like, love, haha, wow, sad, angry)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Reactions table (polymorphic - works for posts, tweets, comments, etc.)
        Schema::create('vp_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->morphs('reactable'); // reactable_id, reactable_type
            $table->enum('type', ['like', 'love', 'haha', 'wow', 'sad', 'angry'])->default('like');
            $table->timestamps();
            
            $table->unique(['user_id', 'reactable_id', 'reactable_type'], 'vp_reactions_unique');
            $table->index(['reactable_type', 'reactable_id', 'type'], 'vp_reactions_poly_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_reactions');
    }
};
