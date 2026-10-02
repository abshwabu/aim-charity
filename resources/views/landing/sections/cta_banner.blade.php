@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $content = is_array($section?->content) ? $section->content : [];
    $badge = $content['badge_text'] ?? ($content['eyebrow'] ?? null);
    $heading = $content['heading'] ?? null;
    $text = $content['text'] ?? ($content['subheading'] ?? null);
    $buttons = is_array($content['buttons'] ?? null) ? $content['buttons'] : [];
@endphp

<x-section-wrapper
    :section="$section"
    anchor="action"
    class="py-16 md:py-24"
    :show-circle-motif="true"
>
    <div class="relative overflow-hidden rounded-theme p-8 sm:p-12 md:p-16 bg-primary text-white text-center shadow-xl border border-primary/20">
        {{-- Subtle Interlocking Circles Motif Overlay --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="motif-circle w-[400px] h-[400px] -top-24 -left-20 border border-white/10"></div>
            <div class="motif-circle w-[500px] h-[500px] -bottom-32 -right-20 border border-accent/20"></div>
        </div>

        <div class="relative z-10 max-w-3xl mx-auto flex flex-col items-center gap-6">
            {{-- Badge --}}
            @if(filled($badge))
                <div>
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase bg-white/15 text-white border border-white/20">
                        {{ $badge }}
                    </span>
                </div>
            @endif

            {{-- Heading --}}
            @if(filled($heading))
                <h2 class="font-heading text-3xl sm:text-4xl md:text-5xl font-normal tracking-tight text-white leading-tight">
                    {{ $heading }}
                </h2>
            @endif

            {{-- Text --}}
            @if(filled($text))
                <p class="text-base sm:text-lg text-white/85 max-w-2xl leading-relaxed font-normal">
                    {{ $text }}
                </p>
            @endif

            {{-- Action Buttons --}}
            @if(count($buttons) > 0)
                <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                    @foreach($buttons as $index => $btn)
                        @php
                            $target = $btn['target'] ?? null;
                            $linkType = $btn['link_type'] ?? 'section';
                            $btnUrl = $linkType === 'section' && filled($target) ? "#{$target}" : ($btn['url'] ?? '#');
                            $btnVariant = match ($btn['style'] ?? null) {
                                'accent' => 'accent',
                                'outline' => 'outline-white',
                                'primary' => ($index === 0 ? 'accent' : 'outline-white'),
                                default => ($index === 0 ? 'accent' : 'outline-white'),
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
            @elseif(filled($content['primary_button_text'] ?? null))
                <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                    <x-button variant="accent" size="lg" :href="$content['primary_button_url'] ?? '#donate'">
                        {{ $content['primary_button_text'] }}
                    </x-button>
                    @if(filled($content['secondary_button_text'] ?? null))
                        <x-button variant="outline-white" size="lg" :href="$content['secondary_button_url'] ?? '#volunteer'">
                            {{ $content['secondary_button_text'] }}
                        </x-button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-section-wrapper>
