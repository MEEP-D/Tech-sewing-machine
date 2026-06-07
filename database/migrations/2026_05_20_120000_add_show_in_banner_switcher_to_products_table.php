<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'show_in_banner_switcher')) {
                $table->boolean('show_in_banner_switcher')->default(false)->after('is_exclusive');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'show_in_banner_switcher')) {
                $table->dropColumn('show_in_banner_switcher');
            }
        });
    }
};
