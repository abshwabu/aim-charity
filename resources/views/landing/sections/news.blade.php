@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $newsPosts = $items ?? collect();
    if ($newsPosts->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
    $buttons = is_array($content['buttons'] ?? null) ? $content['buttons'] : [];
    $count = (int) ($content['items_count'] ?? 3);
    $displayPosts = $newsPosts->take($count > 0 ? $count : 3);
@endphp

<x-section-wrapper
    :section="$section"
    anchor="news"
    class="py-16 md:py-24"
    :show-circle-motif="false"
>
    <div class="flex flex-col gap-12 sm:gap-16">
        {{-- Section Heading --}}
        <div class="max-w-3xl">
            <x-heading-block
                :eyebrow="$eyebrow"
                :heading="$heading"
                :subheading="$subheading"
                size="lg"
            />
        </div>

        {{-- News Articles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($displayPosts as $post)
                <article class="group flex flex-col justify-between rounded-theme bg-surface border border-border shadow-sm overflow-hidden hover:shadow-md hover:border-primary/40 transition-all duration-300">
                    <div class="flex flex-col">
                        {{-- Article Cover Image --}}
                        @if(filled($post->cover_image))
                            <a href="{{ route('news.show', $post->slug) }}" class="block aspect-video w-full overflow-hidden bg-background">
                                <x-image
                                    :src="$post->cover_image"
                                    :alt="$post->title"
                                    aspect="video"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                            </a>
                        @endif

                        <div class="p-6 sm:p-8 flex flex-col gap-3">
                            {{-- Published Date --}}
                            @if($post->published_at)
                                <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs font-semibold tracking-wider uppercase text-accent">
                                    {{ $post->published_at->format('M d, Y') }}
                                </time>
                            @endif

                            {{-- Title --}}
                            <h3 class="font-heading text-xl sm:text-2xl font-normal text-text group-hover:text-primary transition-colors">
                                <a href="{{ route('news.show', $post->slug) }}" class="focus:outline-none focus:underline">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            {{-- Excerpt --}}
                            @if(filled($post->excerpt))
                                <p class="text-sm text-text-muted leading-relaxed line-clamp-3 font-normal">
                                    {{ $post->excerpt }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer Link --}}
                    <div class="p-6 sm:p-8 pt-0">
                        <a
                            href="{{ route('news.show', $post->slug) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-primary group-hover:text-primary-hover transition-colors"
                        >
                            <span class="truncate">{{ $post->title }}</span>
                            <x-icon name="heroicon-o-arrow-right" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" />
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Section Action Buttons --}}
        @if(count($buttons) > 0)
            <div class="flex flex-wrap items-center justify-start gap-4">
                @foreach($buttons as $btn)
                    @php
                        $target = $btn['target'] ?? null;
                        $linkType = $btn['link_type'] ?? 'section';
                        $btnUrl = $linkType === 'section' && filled($target) ? "#{$target}" : ($btn['url'] ?? '#');
                    @endphp
                    @if(filled($btn['label'] ?? null))
                        <x-button
                            :variant="$btn['style'] ?? 'outline'"
                            :href="$btnUrl"
                        >
                            {{ $btn['label'] }}
                        </x-button>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-section-wrapper>
