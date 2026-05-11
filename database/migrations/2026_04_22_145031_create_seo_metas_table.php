<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // seoable_id, seoable_type
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 165)->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->json('schema_markup')->nullable();  // JSON-LD structured data
            $table->boolean('no_index')->default(false);
            $table->boolean('no_follow')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['seoable_id', 'seoable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
