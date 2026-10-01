{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@foreach($posts as $post)
    <url>
        <loc>{{ route('news.show', $post->slug) }}</loc>
        <lastmod>{{ ($post->updated_at ?? $post->published_at ?? now())->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
</urlset>
