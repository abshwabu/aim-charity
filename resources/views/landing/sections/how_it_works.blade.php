@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $steps = $items ?? collect();
    if ($steps->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
    $buttons = is_array($content['buttons'] ?? null) ? $content['buttons'] : [];
@endphp

<x-section-wrapper
    :section="$section"
    anchor="how-it-works"
    class="py-16 md:py-24"
    :show-circle-motif="true"
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

        {{-- Numbered Steps Timeline --}}
        <div class="relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($steps as $index => $step)
                    <div class="relative flex flex-col gap-4 p-6 sm:p-8 rounded-theme bg-surface border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-300">
                        {{-- Step Number & Icon Badge --}}
                        <div class="flex items-center justify-between">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary text-white font-heading text-lg font-bold shadow-sm">
                                {{ $loop->iteration }}
                            </span>

                            @if(filled($step->icon))
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-accent/15 text-accent">
                                    <x-icon :name="$step->icon" class="w-5 h-5" />
                                </div>
                            @endif
                        </div>

                        {{-- Step Title --}}
                        <h3 class="font-heading text-xl font-normal text-text mt-2">
                            {{ $step->title }}
                        </h3>

                        {{-- Step Description --}}
                        @if(filled($step->description))
                            <p class="text-sm text-text-muted leading-relaxed font-normal">
                                {{ $step->description }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Optional Action Buttons --}}
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
                            :variant="$btn['style'] ?? 'primary'"
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
