<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'code')) {
                $table->string('code')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('products', 'long_description')) {
                $table->longText('long_description')->nullable()->after('short_description');
            }
            if (! Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('long_description');
            }
            if (! Schema::hasColumn('products', 'video_id')) {
                $table->string('video_id')->nullable()->after('image');
            }
            if (! Schema::hasColumn('products', 'is_hot')) {
                $table->boolean('is_hot')->default(false)->after('is_new');
            }
            if (! Schema::hasColumn('products', 'is_exclusive')) {
                $table->boolean('is_exclusive')->default(false)->after('is_hot');
            }
        });

        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'hotline')) {
                $table->string('hotline')->nullable()->after('group');
            }
            if (! Schema::hasColumn('settings', 'email')) {
                $table->string('email')->nullable()->after('hotline');
            }
            if (! Schema::hasColumn('settings', 'address')) {
                $table->string('address')->nullable()->after('email');
            }
            if (! Schema::hasColumn('settings', 'social_links')) {
                $table->json('social_links')->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['code', 'long_description', 'image', 'video_id', 'is_hot', 'is_exclusive'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('settings', function (Blueprint $table) {
            $columns = ['hotline', 'email', 'address', 'social_links'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
