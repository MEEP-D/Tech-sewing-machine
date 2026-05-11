<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm index cho view_count trên bảng products và posts.
 *
 * Mục đích: Các query tổng hợp trên dashboard admin như
 *   Product::sum('view_count')  và  Post::sum('view_count')
 * sẽ nhanh hơn khi có index. Đồng thời index status giúp
 * scopePublished() lọc hiệu quả hơn.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Products
        Schema::table('products', function (Blueprint $table) {
            // view_count được tăng bởi user khi xem trang sản phẩm
            if (! $this->hasIndex('products', 'products_view_count_index')) {
                $table->index('view_count', 'products_view_count_index');
            }
            // status index giúp scopePublished() nhanh hơn
            if (! $this->hasIndex('products', 'products_status_index')) {
                $table->index('status', 'products_status_index');
            }
            // is_featured index cho trang chủ (featuredProducts)
            if (! $this->hasIndex('products', 'products_is_featured_index')) {
                $table->index('is_featured', 'products_is_featured_index');
            }
        });

        // Posts
        Schema::table('posts', function (Blueprint $table) {
            // view_count được tăng bởi user khi xem bài viết
            if (! $this->hasIndex('posts', 'posts_view_count_index')) {
                $table->index('view_count', 'posts_view_count_index');
            }
            // Composite index cho scopePublished (status + published_at)
            if (! $this->hasIndex('posts', 'posts_status_published_at_index')) {
                $table->index(['status', 'published_at'], 'posts_status_published_at_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndexIfExists('products_view_count_index');
            $table->dropIndexIfExists('products_status_index');
            $table->dropIndexIfExists('products_is_featured_index');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndexIfExists('posts_view_count_index');
            $table->dropIndexIfExists('posts_status_published_at_index');
        });
    }

    /**
     * Kiểm tra index đã tồn tại chưa để tránh lỗi khi chạy lại.
     */
    private function hasIndex(string $table, string $index): bool
    {
        $indexes = collect(Schema::getIndexes($table))
            ->pluck('name')
            ->toArray();

        return in_array($index, $indexes, true);
    }
};
