<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Menus Migration
 * 
 * Creates tables for menu management system
 */
return new class extends Migration
{
    public function up(): void
    {
        // Menus table
        Schema::create('vp_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->string('location')->nullable(); // primary, footer, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Menu items table
        Schema::create('vp_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('vp_menus')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->string('target')->default('_self'); // _self, _blank
            $table->integer('parent_id')->nullable()->index();
            $table->integer('order')->default(0);
            $table->string('icon')->nullable();
            $table->json('attributes')->nullable(); // custom attributes
            $table->timestamps();
            
            $table->index(['menu_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_menu_items');
        Schema::dropIfExists('vp_menus');
    }
};
