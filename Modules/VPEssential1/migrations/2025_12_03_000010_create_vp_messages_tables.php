<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Messaging System Migration
 * 
 * Creates tables for private messaging between users
 */
return new class extends Migration
{
    public function up(): void
    {
        // Conversations table
        Schema::create('vp_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // For group conversations
            $table->enum('type', ['private', 'group'])->default('private');
            $table->timestamps();
        });
        
        // Conversation participants table
        Schema::create('vp_conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('vp_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->timestamps();
            
            $table->unique(['conversation_id', 'user_id'], 'vp_conv_participant_unique');
            $table->index('user_id');
        });
        
        // Messages table
        Schema::create('vp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('vp_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->json('attachments')->nullable(); // Array of file URLs
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['conversation_id', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_messages');
        Schema::dropIfExists('vp_conversation_participants');
        Schema::dropIfExists('vp_conversations');
    }
};
