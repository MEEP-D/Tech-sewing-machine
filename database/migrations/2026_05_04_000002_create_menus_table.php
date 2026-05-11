<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('location')->default('header');
            $table->string('label');
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->string('target')->default('_self');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('icon')->nullable();
            $table->string('css_class')->nullable();
            $table->json('meta_config')->nullable();
            $table->timestamps();

            $table->index(['location', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
