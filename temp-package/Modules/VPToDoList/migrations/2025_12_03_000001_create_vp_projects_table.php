<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#dc2626'); // Hex color
            $table->enum('status', ['active', 'on_hold', 'completed', 'archived'])->default('active');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_projects');
    }
};
