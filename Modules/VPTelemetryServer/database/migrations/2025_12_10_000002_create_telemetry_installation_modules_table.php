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
        Schema::create('telemetry_installation_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained('telemetry_installations')->onDelete('cascade');
            $table->string('module_name', 100);
            $table->timestamps();

            // Prevent duplicate module entries (shorter index name)
            $table->unique(['installation_id', 'module_name'], 'telem_inst_mod_unique');
            
            // Index for statistics
            $table->index('module_name', 'telem_mod_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_installation_modules');
    }
};
