<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'discount_percent')) {
                $table->unsignedTinyInteger('discount_percent')->default(0)->after('view_count');
            }

            if (! Schema::hasColumn('products', 'installment_percent')) {
                $table->unsignedTinyInteger('installment_percent')->default(0)->after('discount_percent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'installment_percent')) {
                $table->dropColumn('installment_percent');
            }

            if (Schema::hasColumn('products', 'discount_percent')) {
                $table->dropColumn('discount_percent');
            }
        });
    }
};
