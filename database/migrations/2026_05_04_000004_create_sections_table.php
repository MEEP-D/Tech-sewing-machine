<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('type')->default('content');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('container_class')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('spacing_top')->nullable();
            $table->string('spacing_bottom')->nullable();
            $table->json('style_config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
