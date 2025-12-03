<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique(); // e.g., "EMP-2024-001"
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('specialization')->nullable();
            $table->date('hire_date');
            $table->enum('employment_status', ['Full-time', 'Part-time', 'Contract'])->default('Full-time');
            $table->enum('status', ['Active', 'Inactive', 'Retired'])->default('Active');
            $table->timestamps();
            
            $table->index(['department_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
