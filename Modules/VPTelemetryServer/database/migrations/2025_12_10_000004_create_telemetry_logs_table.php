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
        Schema::create('telemetry_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained('telemetry_installations')->onDelete('cascade');
            $table->string('event_type', 50)->index(); // install, update, module_change, heartbeat
            $table->json('payload'); // Full request payload
            $table->timestamps();

            // Indexes for common queries
            $table->index('created_at');
            $table->index(['installation_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_logs');
    }
};
