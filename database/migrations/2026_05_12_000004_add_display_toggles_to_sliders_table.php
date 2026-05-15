<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->boolean('show_overlay')->default(false)->after('is_active');
            $table->boolean('show_title')->default(true)->after('show_overlay');
            $table->boolean('show_subtitle')->default(true)->after('show_title');
            $table->boolean('show_button')->default(true)->after('show_subtitle');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['show_overlay', 'show_title', 'show_subtitle', 'show_button']);
        });
    }
};
