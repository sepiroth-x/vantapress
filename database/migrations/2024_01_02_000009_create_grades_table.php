<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->decimal('midterm_grade', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->decimal('final_rating', 5, 2)->nullable(); // Computed average
            $table->enum('remarks', ['Passed', 'Failed', 'INC', 'DRP'])->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            
            $table->index('enrollment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
