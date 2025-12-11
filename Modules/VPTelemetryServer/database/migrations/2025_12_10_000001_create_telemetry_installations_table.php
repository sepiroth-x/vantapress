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
        Schema::create('telemetry_installations', function (Blueprint $table) {
            $table->id();
            $table->string('installation_id', 36)->unique()->index(); // UUID from sender
            $table->string('domain')->nullable();
            $table->string('ip', 45)->nullable(); // IPv6 compatible
            $table->string('version', 50)->nullable();
            $table->string('php_version', 50)->nullable();
            $table->string('server_os', 100)->nullable();
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('last_ping_at')->nullable()->index();
            $table->timestamp('updated_at_version')->nullable(); // When version was last updated
            $table->timestamps();

            // Indexes for common queries
            $table->index('domain');
            $table->index('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telemetry_installations');
    }
};
