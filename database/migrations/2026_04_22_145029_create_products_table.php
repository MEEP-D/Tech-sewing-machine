<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('price')->nullable();          // stored as string for flexibility (VND, negotiable)
            $table->string('brand')->nullable();
            $table->string('origin')->nullable();         // xuất xứ
            $table->json('specifications')->nullable();   // thông số kỹ thuật
            $table->string('thumbnail')->nullable();
            $table->json('gallery')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
            $table->index('slug');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
