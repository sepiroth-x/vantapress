<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('vp_projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'review', 'completed', 'blocked'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->json('tags')->nullable();
            $table->timestamps();
            
            $table->index(['project_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_tasks');
    }
};
