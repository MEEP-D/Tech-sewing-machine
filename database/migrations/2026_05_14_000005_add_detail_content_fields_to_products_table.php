<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'support_prompt')) {
                $table->string('support_prompt')->nullable()->after('long_description');
            }
            if (! Schema::hasColumn('products', 'cta_primary_label')) {
                $table->string('cta_primary_label')->nullable()->after('support_prompt');
            }
            if (! Schema::hasColumn('products', 'cta_primary_url')) {
                $table->string('cta_primary_url')->nullable()->after('cta_primary_label');
            }
            if (! Schema::hasColumn('products', 'cta_secondary_label')) {
                $table->string('cta_secondary_label')->nullable()->after('cta_primary_url');
            }
            if (! Schema::hasColumn('products', 'cta_secondary_url')) {
                $table->string('cta_secondary_url')->nullable()->after('cta_secondary_label');
            }
            if (! Schema::hasColumn('products', 'overview_heading')) {
                $table->string('overview_heading')->nullable()->after('cta_secondary_url');
            }
            if (! Schema::hasColumn('products', 'overview_content')) {
                $table->longText('overview_content')->nullable()->after('overview_heading');
            }
            if (! Schema::hasColumn('products', 'seo_heading')) {
                $table->string('seo_heading')->nullable()->after('overview_content');
            }
            if (! Schema::hasColumn('products', 'seo_content')) {
                $table->longText('seo_content')->nullable()->after('seo_heading');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'support_prompt',
                'cta_primary_label',
                'cta_primary_url',
                'cta_secondary_label',
                'cta_secondary_url',
                'overview_heading',
                'overview_content',
                'seo_heading',
                'seo_content',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
