<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('type', ['news', 'event', 'fair', 'seminar'])->default('news');
            $table->timestamp('published_at')->nullable();
            $table->string('event_date')->nullable();     // Ngày diễn ra (hội chợ, hội thảo)
            $table->string('event_location')->nullable(); // Địa điểm sự kiện
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'type']);
            $table->index('slug');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
