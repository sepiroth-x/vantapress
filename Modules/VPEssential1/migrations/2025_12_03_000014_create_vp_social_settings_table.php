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
        DB::table('vp_social_settings')->insert([
            // Feature toggles
            ['key' => 'enable_registration', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Allow new user registration'],
            ['key' => 'enable_profiles', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable user profiles'],
            ['key' => 'enable_friends', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable friend system'],
            ['key' => 'enable_followers', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable follower system'],
            ['key' => 'enable_pokes', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable poke feature'],
            ['key' => 'enable_posts', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable posts/newsfeed'],
            ['key' => 'enable_tweets', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable tweets'],
            ['key' => 'enable_comments', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable comments'],
            ['key' => 'enable_reactions', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable reactions'],
            ['key' => 'enable_sharing', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable post sharing'],
            ['key' => 'enable_hashtags', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable hashtags'],
            ['key' => 'enable_messaging', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable private messaging'],
            ['key' => 'enable_notifications', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable notifications'],
            ['key' => 'enable_verification', 'value' => 'true', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable verification badges'],
            
            // General settings
            ['key' => 'max_post_length', 'value' => '5000', 'type' => 'integer', 'group' => 'general', 'description' => 'Maximum post content length'],
            ['key' => 'max_tweet_length', 'value' => '280', 'type' => 'integer', 'group' => 'general', 'description' => 'Maximum tweet content length'],
            ['key' => 'posts_per_page', 'value' => '20', 'type' => 'integer', 'group' => 'general', 'description' => 'Posts per newsfeed page'],
            
            // Timestamps
            ['created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_social_settings');
    }
};
