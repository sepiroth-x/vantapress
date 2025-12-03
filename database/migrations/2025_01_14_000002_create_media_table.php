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
        // Check if table already exists
        if (Schema::hasTable('media')) {
            return;
        }
        
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_name');
            $table->string('path');
            $table->string('mime_type');
            $table->bigInteger('size')->unsigned();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->text('description')->nullable();
            $table->string('caption')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('mime_type');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
