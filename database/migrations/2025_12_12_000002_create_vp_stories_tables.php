<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['image', 'video', 'text'])->default('image');
            $table->string('media_url')->nullable(); // For image/video stories
            $table->text('content')->nullable(); // For text stories
            $table->string('background_color')->nullable(); // For text stories
            $table->integer('duration')->default(5); // Seconds to display
            $table->json('viewers')->nullable(); // Array of user IDs who viewed
            $table->integer('views_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('expires_at');
            $table->index('created_at');
        });
        
        Schema::create('vp_story_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained('vp_stories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            $table->unique(['story_id', 'user_id']);
            $table->index('viewed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_story_views');
        Schema::dropIfExists('vp_stories');
    }
};
