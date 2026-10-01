@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $partners = $items ?? collect();
    if ($partners->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? ($content['intro'] ?? null);
@endphp

<x-section-wrapper
    :section="$section"
    anchor="partners"
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

        {{-- Partners Logo Strip / Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
            @foreach($partners as $partner)
                <div class="flex items-center justify-center p-4 sm:p-6 rounded-theme bg-surface border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-300 text-center min-h-[90px]">
                    @if(filled($partner->url))
                        <a
                            href="{{ $partner->url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex flex-col items-center justify-center gap-2 group w-full focus:outline-none focus:ring-2 focus:ring-primary rounded p-1"
                        >
                            @if(filled($partner->logo))
                                <x-image :src="$partner->logo" :alt="$partner->name" class="h-8 max-h-8 w-auto object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300" />
                            @else
                                <span class="font-heading text-xs sm:text-sm font-medium text-text-muted group-hover:text-primary transition-colors line-clamp-2">
                                    {{ $partner->name }}
                                </span>
                            @endif
                        </a>
                    @else
                        <div class="flex flex-col items-center justify-center gap-2 w-full">
                            @if(filled($partner->logo))
                                <x-image :src="$partner->logo" :alt="$partner->name" class="h-8 max-h-8 w-auto object-contain" />
                            @else
                                <span class="font-heading text-xs sm:text-sm font-medium text-text-muted line-clamp-2">
                                    {{ $partner->name }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
