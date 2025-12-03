<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // e.g., "2024-00001"
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->integer('year_level'); // 1, 2, 3, 4
            $table->enum('status', ['Active', 'Inactive', 'Graduated', 'Dropped'])->default('Active');
            $table->date('enrollment_date');
            $table->timestamps();
            
            $table->index(['department_id', 'year_level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
