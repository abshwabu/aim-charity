@props([
    'settings' => null,
    'hero' => null,
])

@php
    $content = $hero?->content ?? [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
    $buttons = is_array($content['buttons'] ?? null) ? $content['buttons'] : [];
    $images = is_array($content['images'] ?? null) ? $content['images'] : [];
@endphp

<x-section-wrapper :section="$hero" anchor="hero" class="relative pt-8 pb-16 md:pt-16 md:pb-24" :show-circle-motif="true">
    <div class="flex flex-col items-center text-center max-w-4xl mx-auto">
        <x-heading-block
            :eyebrow="$eyebrow"
            :heading="$heading"
            :subheading="$subheading"
            align="center"
            size="lg"
            as="h1"
            class="reveal-on-scroll"
        />

        @if(count($buttons) > 0)
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4 reveal-on-scroll reveal-delay-1">
                @foreach($buttons as $index => $btn)
                    @if(filled($btn['label'] ?? null) && filled($btn['url'] ?? null))
                        @php
                            $btnVariant = match ($btn['style'] ?? null) {
                                'secondary' => 'secondary',
                                'outline' => 'outline',
                                'accent' => 'accent',
                                default => ($index === 0 ? 'primary' : 'outline'),
                            };
                        @endphp
                        <x-button
                            :variant="$btnVariant"
                            size="lg"
                            :href="$btn['url']"
                        >
                            {{ $btn['label'] }}
                        </x-button>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-section-wrapper>
