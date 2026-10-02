@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
    $explainer = $content['coalition_explainer'] ?? ($content['explainer'] ?? null);
    $story = $content['story'] ?? ($content['body'] ?? null);
    $mission = $content['mission'] ?? null;
    $vision = $content['vision'] ?? null;
    $values = is_array($content['values'] ?? null) ? $content['values'] : [];
    $buttons = is_array($content['buttons'] ?? null) ? $content['buttons'] : [];
    $images = is_array($content['images'] ?? null) ? $content['images'] : [];
    $memberGroups = $allItems['member_groups'] ?? collect();
@endphp

<x-section-wrapper
    :section="$section"
    anchor="about"
    class="py-16 md:py-24"
    :show-circle-motif="true"
>
    <div class="flex flex-col gap-16 md:gap-24">
        {{-- Section Heading --}}
        <div class="max-w-3xl">
            <x-heading-block
                :eyebrow="$eyebrow"
                :heading="$heading"
                :subheading="$subheading"
                size="lg"
            />
        </div>

        {{-- "Group of Groups" Explainer & Converging Circles Diagram --}}
        @if(filled($explainer) || $memberGroups->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center rounded-theme p-6 sm:p-10 md:p-12 bg-surface border border-border shadow-sm">
                {{-- Left Text Explainer --}}
                <div class="lg:col-span-6 flex flex-col gap-6">
                    @if(filled($explainer))
                        <div class="prose prose-lg text-text leading-relaxed font-normal">
                            <p>{{ $explainer }}</p>
                        </div>
                    @endif

                    @if(count($buttons) > 0)
                        <div class="flex flex-wrap gap-4 pt-2">
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

                {{-- Right Visual: Town Friends Community Motif --}}
                <div class="lg:col-span-6 flex items-center justify-center py-6">
                    <div class="relative w-72 h-72 sm:w-80 sm:h-80 flex items-center justify-center" aria-hidden="true">
                        {{-- Outer Concentric Guide Ring --}}
                        <div class="absolute inset-0 rounded-full border border-dashed border-primary/25 animate-[spin_60s_linear_infinite]"></div>
                        <div class="absolute inset-6 rounded-full border border-primary/15"></div>

                        {{-- Central Association Circle --}}
                        <div class="relative z-10 w-36 h-36 sm:w-40 sm:h-40 rounded-full bg-primary text-white flex flex-col items-center justify-center p-4 text-center shadow-lg ring-4 ring-primary/20">
                            <svg class="w-10 h-10 text-accent mb-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            @if(filled($settings?->branding['site_name'] ?? null))
                                <span class="text-xs font-bold tracking-tight uppercase leading-tight line-clamp-2">
                                    {{ $settings->branding['site_name'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mission & Vision Cards --}}
        @if(filled($mission) || filled($vision))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @if(filled($mission))
                    <div class="flex flex-col gap-4 p-8 rounded-theme bg-surface border border-border shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <x-icon name="heroicon-o-compass" class="w-5 h-5" />
                            </div>
                        </div>
                        <p class="text-base sm:text-lg text-text/85 leading-relaxed font-normal">
                            {{ $mission }}
                        </p>
                    </div>
                @endif

                @if(filled($vision))
                    <div class="flex flex-col gap-4 p-8 rounded-theme bg-surface border border-border shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-accent/15 text-accent">
                                <x-icon name="heroicon-o-eye" class="w-5 h-5" />
                            </div>
                        </div>
                        <p class="text-base sm:text-lg text-text/85 leading-relaxed font-normal">
                            {{ $vision }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Story Rich Text --}}
        @if(filled($story))
            <div class="p-8 sm:p-10 rounded-theme bg-surface/50 border border-border">
                <x-rich-text :content="$story" class="prose-lg max-w-none text-text" />
            </div>
        @endif

        {{-- Core Values Grid --}}
        @if(count($values) > 0)
            <div class="flex flex-col gap-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($values as $val)
                        @if(filled($val['title'] ?? null) || filled($val['text'] ?? null))
                            <div class="flex flex-col gap-3 p-6 rounded-theme bg-surface border border-border shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/30">
                                @if(filled($val['icon'] ?? null))
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary mb-1">
                                        <x-icon :name="$val['icon']" class="w-5 h-5" />
                                    </div>
                                @endif
                                @if(filled($val['title'] ?? null))
                                    <h3 class="font-heading text-xl font-normal text-text">
                                        {{ $val['title'] }}
                                    </h3>
                                @endif
                                @if(filled($val['text'] ?? null))
                                    <p class="text-sm text-text-muted leading-relaxed font-normal">
                                        {{ $val['text'] }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Showcase Photos --}}
        @if(count($images) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($images as $img)
                    @if(filled($img))
                        <div class="overflow-hidden rounded-theme shadow-sm border border-border aspect-square">
                            <x-image :src="$img" aspect="square" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</x-section-wrapper>
