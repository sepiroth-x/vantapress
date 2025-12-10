<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('layout_templates', function (Blueprint $table) {
            // Drop foreign key if it exists
            if (Schema::hasColumn('layout_templates', 'theme_id')) {
                // Try to drop foreign key constraint first (may not exist)
                try {
                    $table->dropForeign(['theme_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                
                // Drop the theme_id column
                $table->dropColumn('theme_id');
            }
            
            // Add theme_slug column if it doesn't exist
            if (!Schema::hasColumn('layout_templates', 'theme_slug')) {
                $table->string('theme_slug')->nullable()->after('is_global');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layout_templates', function (Blueprint $table) {
            // Reverse the changes
            if (Schema::hasColumn('layout_templates', 'theme_slug')) {
                $table->dropColumn('theme_slug');
            }
            
            if (!Schema::hasColumn('layout_templates', 'theme_id')) {
                $table->foreignId('theme_id')->nullable()->after('is_global');
            }
        });
    }
};
