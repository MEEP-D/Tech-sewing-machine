<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('products.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('news.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    @foreach($categories as $category)
        <url>
            <loc>{{ $category->url }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach($products as $product)
        <url>
            <loc>{{ route('products.show', $product->slug) }}</loc>
            <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach

    @foreach($posts as $post)
        <url>
            <loc>{{ route('news.show', $post->slug) }}</loc>
            <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
