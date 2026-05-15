<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'support_prompt')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->text('support_prompt')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'support_prompt')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('support_prompt')->nullable()->change();
        });
    }
};
