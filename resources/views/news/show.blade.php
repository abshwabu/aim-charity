<x-layouts.landing
    :settings="$settings"
    :title="$post->title"
    :description="$post->excerpt"
    :post="$post"
    :og-image="filled($post->cover_image) ? \App\Support\Site::imageUrl($post->cover_image) : null"
    :canonical="route('news.show', $post->slug)"
    og-type="article"
>
    <article class="py-12 md:py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Navigation --}}
        <div class="mb-8">
            <a
                href="{{ route('home') }}#news"
                class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-primary-hover focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded"
                aria-label="{{ route('home') }}#news"
            >
                <x-icon name="heroicon-o-arrow-left" class="w-4 h-4" />
            </a>
        </div>

        {{-- Published Date --}}
        @if($post->published_at)
            <div class="mb-4">
                <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs font-semibold tracking-wider uppercase text-accent">
                    {{ $post->published_at->format('F j, Y') }}
                </time>
            </div>
        @endif

        {{-- Headline --}}
        <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-normal text-text leading-tight mb-6">
            {{ $post->title }}
        </h1>

        {{-- Lead Excerpt --}}
        @if(filled($post->excerpt))
            <p class="text-lg sm:text-xl text-text-muted leading-relaxed mb-8">
                {{ $post->excerpt }}
            </p>
        @endif

        {{-- Cover Image --}}
        @if(filled($post->cover_image))
            <div class="mb-10 overflow-hidden rounded-theme shadow-md border border-border">
                <x-image
                    :src="$post->cover_image"
                    :alt="$post->title"
                    aspect="video"
                    loading="eager"
                    class="w-full"
                />
            </div>
        @endif

        {{-- Article Body --}}
        @if(filled($post->body))
            <x-rich-text :content="$post->body" class="prose-lg max-w-none text-text" />
        @endif
    </article>
</x-layouts.landing>
