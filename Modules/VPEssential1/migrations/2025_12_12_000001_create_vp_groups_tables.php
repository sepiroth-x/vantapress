<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('privacy', ['public', 'private', 'secret'])->default('public');
            $table->enum('post_permissions', ['all_members', 'admins_only'])->default('all_members');
            $table->boolean('is_verified')->default(false);
            $table->integer('members_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('privacy');
            $table->index('created_by');
        });
        
        Schema::create('vp_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('vp_groups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['admin', 'moderator', 'member'])->default('member');
            $table->enum('status', ['pending', 'approved', 'banned'])->default('approved');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            
            $table->unique(['group_id', 'user_id']);
            $table->index(['group_id', 'status']);
            $table->index(['user_id', 'status']);
        });
        
        Schema::create('vp_group_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('vp_groups')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('vp_posts')->onDelete('cascade');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            $table->unique(['group_id', 'post_id']);
            $table->index('is_pinned');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_group_posts');
        Schema::dropIfExists('vp_group_members');
        Schema::dropIfExists('vp_groups');
    }
};
