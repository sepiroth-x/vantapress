<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Room 101", "Lab 1"
            $table->string('building')->nullable();
            $table->integer('capacity')->default(30);
            $table->enum('type', ['Classroom', 'Laboratory', 'Auditorium', 'Library'])->default('Classroom');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->index('is_available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
