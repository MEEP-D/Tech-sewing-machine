<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'promotion_title')) {
                $table->string('promotion_title')->nullable()->after('installment_percent');
            }

            if (! Schema::hasColumn('products', 'promotion_description')) {
                $table->text('promotion_description')->nullable()->after('promotion_title');
            }

            if (! Schema::hasColumn('products', 'promotion_gift_name')) {
                $table->string('promotion_gift_name')->nullable()->after('promotion_description');
            }

            if (! Schema::hasColumn('products', 'promotion_gift_image')) {
                $table->string('promotion_gift_image')->nullable()->after('promotion_gift_name');
            }

            if (! Schema::hasColumn('products', 'promotion_starts_at')) {
                $table->timestamp('promotion_starts_at')->nullable()->after('promotion_gift_image');
            }

            if (! Schema::hasColumn('products', 'promotion_ends_at')) {
                $table->timestamp('promotion_ends_at')->nullable()->after('promotion_starts_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'promotion_title',
                'promotion_description',
                'promotion_gift_name',
                'promotion_gift_image',
                'promotion_starts_at',
                'promotion_ends_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
