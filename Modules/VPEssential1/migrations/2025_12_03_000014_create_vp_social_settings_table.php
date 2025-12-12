<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Social Settings Migration
 * 
 * Creates table for module feature toggles and configuration
 */
return new class extends Migration
{
    public function up(): void
    {
        // Only create table if it doesn't exist
        if (!Schema::hasTable('vp_social_settings')) {
            Schema::create('vp_social_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('boolean'); // boolean, string, json
                $table->string('group')->default('general'); // general, features, permissions
                $table->text('description')->nullable();
                $table->timestamps();
                
                $table->index('group');
            });
            
            // Insert default settings
            $timestamp = now();
            DB::table('vp_social_settings')->insert([
                // Feature toggles
                ['key' => 'enable_registration', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Allow new user registration', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_profiles', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable user profiles', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_friends', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable friend system', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_followers', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable follower system', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_pokes', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable poke feature', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_posts', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable posts/newsfeed', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_tweets', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable tweets', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_comments', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable comments', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_reactions', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable reactions', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_sharing', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable post sharing', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_hashtags', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable hashtags', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_messaging', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable private messaging', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_notifications', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable notifications', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'enable_verification', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable verification badges', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                
                // General settings
                ['key' => 'max_post_length', 'value' => '5000', 'type' => 'integer', 'group' => 'general', 'description' => 'Maximum post content length', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'max_tweet_length', 'value' => '280', 'type' => 'integer', 'group' => 'general', 'description' => 'Maximum tweet content length', 'created_at' => $timestamp, 'updated_at' => $timestamp],
                ['key' => 'posts_per_page', 'value' => '20', 'type' => 'integer', 'group' => 'general', 'description' => 'Posts per newsfeed page', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_social_settings');
    }
};
