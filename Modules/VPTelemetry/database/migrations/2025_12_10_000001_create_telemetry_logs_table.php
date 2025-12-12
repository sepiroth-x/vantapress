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
        if (!Schema::hasTable('telemetry_logs')) {
            Schema::create('telemetry_logs', function (Blueprint $table) {
                $table->id();
                $table->string('event_type', 50)->index(); // install, update, heartbeat, module_change
                $table->json('payload'); // Full telemetry data sent
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                // Index for querying recent logs
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_logs');
    }
};
