<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * VP Essential 1 - Theme Settings Migration
 * 
 * Creates table for storing theme customization settings
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vp_theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, json, boolean, integer
            $table->string('group')->default('general'); // general, hero, colors, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vp_theme_settings');
    }
};
