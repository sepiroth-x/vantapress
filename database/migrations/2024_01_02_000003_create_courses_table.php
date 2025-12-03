<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // e.g., "IT101"
            $table->string('name'); // e.g., "Introduction to Programming"
            $table->text('description')->nullable();
            $table->integer('units')->default(3);
            $table->integer('year_level'); // 1, 2, 3, 4
            $table->enum('semester', ['1st', '2nd', 'Summer']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['department_id', 'year_level', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
