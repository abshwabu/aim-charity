@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? $content['headline'] ?? null;
    $subheading = $content['subheading'] ?? $content['subheadline'] ?? null;
    $buttons = is_array($content['buttons'] ?? null) ? $content['buttons'] : [];
    $statChips = is_array($content['stat_chips'] ?? null) ? $content['stat_chips'] : [];
    $collageImages = is_array($content['collage_images'] ?? null) ? $content['collage_images'] : [];
    $videoUrl = $content['video_url'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="hero"
    class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden"
    :show-circle-motif="true"
>
    <div class="flex flex-col items-center text-center max-w-5xl mx-auto">
        {{-- Coalition Interlocking Rings Symbol Motif --}}
        <div class="relative mb-6 flex items-center justify-center -space-x-3" aria-hidden="true">
            <div class="w-10 h-10 rounded-full border-2 border-primary/40 bg-primary/10"></div>
            <div class="w-12 h-12 rounded-full border-2 border-accent/60 bg-accent/15 -translate-y-1"></div>
            <div class="w-10 h-10 rounded-full border-2 border-secondary/40 bg-secondary/10"></div>
        </div>

        {{-- Editorial Hero Heading Block --}}
        <x-heading-block
            :eyebrow="$eyebrow"
            :heading="$heading"
            :subheading="$subheading"
            align="center"
            size="xl"
            as="h1"
            class="reveal-on-scroll"
        />

        {{-- Hero Action Buttons --}}
        @if(count($buttons) > 0)
            <div class="mt-8 sm:mt-10 flex flex-wrap items-center justify-center gap-4 reveal-on-scroll reveal-delay-1">
                @foreach($buttons as $index => $btn)
                    @php
                        $target = $btn['target'] ?? null;
                        $linkType = $btn['link_type'] ?? 'section';
                        $btnUrl = $linkType === 'section' && filled($target) ? "#{$target}" : ($btn['url'] ?? '#');
                        $btnVariant = match ($btn['style'] ?? null) {
                            'secondary' => 'secondary',
                            'outline' => 'outline',
                            'accent' => 'accent',
                            default => ($index === 0 ? 'primary' : 'outline'),
                        };
                    @endphp
                    @if(filled($btn['label'] ?? null))
                        <x-button
                            :variant="$btnVariant"
                            size="lg"
                            :href="$btnUrl"
                        >
                            {{ $btn['label'] }}
                        </x-button>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Floating Stat Chips --}}
        @if(count($statChips) > 0)
            <div class="mt-12 sm:mt-16 w-full max-w-4xl grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 reveal-on-scroll reveal-delay-2">
                @foreach($statChips as $chip)
                    @if(filled($chip['value'] ?? null) && filled($chip['label'] ?? null))
                        <div class="flex items-center gap-3.5 p-4 rounded-theme bg-surface/80 backdrop-blur-sm border border-border shadow-sm text-left">
                            @if(filled($chip['icon'] ?? null))
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-accent/15 text-accent">
                                    <x-icon :name="$chip['icon']" class="w-5 h-5" />
                                </div>
                            @endif
                            <div class="flex flex-col min-w-0">
                                <span class="font-heading text-xl sm:text-2xl font-bold tracking-tight text-text">
                                    {{ $chip['value'] }}
                                </span>
                                <span class="text-xs text-text-muted font-medium truncate">
                                    {{ $chip['label'] }}
                                </span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Hero Collage Images if configured --}}
        @if(count($collageImages) > 0)
            <div class="mt-12 sm:mt-16 w-full grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 reveal-on-scroll reveal-delay-3">
                @foreach($collageImages as $cImage)
                    @if(filled($cImage))
                        <div class="overflow-hidden rounded-theme shadow-md border border-border aspect-square">
                            <x-image
                                :src="$cImage"
                                :alt="$heading ?? ''"
                                aspect="square"
                                loading="eager"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                            />
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-section-wrapper>
