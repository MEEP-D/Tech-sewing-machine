<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'usage_guide_content')) {
                $table->longText('usage_guide_content')->nullable()->after('seo_content');
            }

            if (! Schema::hasColumn('products', 'usage_guide_video_id')) {
                $table->string('usage_guide_video_id')->nullable()->after('usage_guide_content');
            }

            if (! Schema::hasColumn('products', 'usage_guide_attachment')) {
                $table->string('usage_guide_attachment')->nullable()->after('usage_guide_video_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'usage_guide_content',
                'usage_guide_video_id',
                'usage_guide_attachment',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
