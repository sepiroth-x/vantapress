<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vp_user_profiles', function (Blueprint $table) {
            $table->enum('privacy', ['public', 'friends_only', 'private'])->default('public')->after('linkedin');
        });
    }

    public function down(): void
    {
        Schema::table('vp_user_profiles', function (Blueprint $table) {
            $table->dropColumn('privacy');
        });
    }
};
