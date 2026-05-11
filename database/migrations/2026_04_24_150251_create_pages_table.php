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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('layout')->default('default');
            $table->string('layout_mode')->default('content');
            $table->string('container_class')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('spacing_top')->nullable();
            $table->string('spacing_bottom')->nullable();
            $table->boolean('cache_enabled')->default(true);
            $table->integer('cache_ttl')->default(3600);
            $table->json('style_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
