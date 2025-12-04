<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Widgets Migration
 * 
 * Creates tables for widget area management
 */
return new class extends Migration
{
    public function up(): void
    {
        // Widget areas table
        Schema::create('vp_widget_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Widgets table
        Schema::create('vp_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_area_id')->constrained('vp_widget_areas')->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // text, html, menu, recent_posts, etc.
            $table->text('content')->nullable();
            $table->json('settings')->nullable(); // widget-specific settings
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['widget_area_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_widgets');
        Schema::dropIfExists('vp_widget_areas');
    }
};
