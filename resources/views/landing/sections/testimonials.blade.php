@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $testimonials = $items ?? collect();
    if ($testimonials->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="testimonials"
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

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($testimonials as $testimonial)
                <div class="flex flex-col justify-between p-6 sm:p-8 rounded-theme bg-surface border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-300">
                    <div class="flex flex-col gap-4">
                        {{-- Quote Icon --}}
                        <div class="text-accent/40" aria-hidden="true">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>

                        {{-- Quote Text --}}
                        <blockquote class="text-base text-text/90 leading-relaxed font-normal italic">
                            {{ $testimonial->quote }}
                        </blockquote>
                    </div>

                    {{-- Author Meta --}}
                    <div class="mt-6 pt-4 border-t border-border flex items-center gap-4">
                        @if(filled($testimonial->author_photo))
                            <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-border">
                                <x-image
                                    :src="$testimonial->author_photo"
                                    :alt="$testimonial->author_name"
                                    aspect="square"
                                    rounded="rounded-full"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        @endif

                        <div class="flex flex-col min-w-0">
                            <span class="font-heading text-base font-bold text-text truncate">
                                {{ $testimonial->author_name }}
                            </span>

                            @if(filled($testimonial->author_role))
                                <span class="text-xs text-text-muted truncate">
                                    {{ $testimonial->author_role }}
                                </span>
                            @endif

                            @if($testimonial->memberGroup)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-primary/10 text-primary border border-primary/20">
                                        {{ $testimonial->memberGroup->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
