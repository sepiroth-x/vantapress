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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable()->index();
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->boolean('autoload')->default(true);
            $table->timestamps();

            $table->unique(['group', 'key']);
            $table->index(['group', 'autoload']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
