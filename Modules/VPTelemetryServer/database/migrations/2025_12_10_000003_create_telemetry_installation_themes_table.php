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
        Schema::create('telemetry_installation_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained('telemetry_installations')->onDelete('cascade');
            $table->string('theme_name', 100);
            $table->timestamps();

            // Prevent duplicate theme entries (shorter index name)
            $table->unique(['installation_id', 'theme_name'], 'telem_inst_theme_unique');
            
            // Index for statistics
            $table->index('theme_name', 'telem_theme_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_installation_themes');
    }
};
