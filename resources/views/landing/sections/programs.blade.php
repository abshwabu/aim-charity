@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $programs = $items ?? collect();
    if ($programs->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="programs"
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

        {{-- Programs Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($programs as $program)
                <div class="group flex flex-col justify-between rounded-theme bg-surface border border-border shadow-sm overflow-hidden hover:shadow-md hover:border-primary/40 transition-all duration-300">
                    <div class="flex flex-col">
                        {{-- Cover Image if present --}}
                        @if(filled($program->image))
                            <div class="aspect-video w-full overflow-hidden bg-background">
                                <x-image
                                    :src="$program->image"
                                    :alt="$program->title"
                                    aspect="video"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                            </div>
                        @endif

                        <div class="p-6 sm:p-8 flex flex-col gap-4">
                            {{-- Program Icon --}}
                            @if(filled($program->icon))
                                <div class="flex h-12 w-12 items-center justify-center rounded-theme bg-primary/10 text-primary">
                                    <x-icon :name="$program->icon" class="w-6 h-6" />
                                </div>
                            @endif

                            {{-- Title --}}
                            <h3 class="font-heading text-xl sm:text-2xl font-normal text-text group-hover:text-primary transition-colors">
                                {{ $program->title }}
                            </h3>

                            {{-- Description --}}
                            @if(filled($program->description))
                                <p class="text-sm text-text-muted leading-relaxed font-normal">
                                    {{ $program->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Program Action Link --}}
                    @if(filled($program->link_label) && filled($program->link_url))
                        <div class="p-6 sm:p-8 pt-0">
                            <x-button
                                variant="outline"
                                size="sm"
                                :href="$program->link_url"
                                icon="heroicon-o-arrow-right"
                                icon-position="right"
                                class="w-full"
                            >
                                {{ $program->link_label }}
                            </x-button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
