<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "2024-2025"
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('semester', ['1st', '2nd', 'Summer'])->default('1st');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
