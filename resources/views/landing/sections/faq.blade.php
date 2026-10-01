@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $faqs = $items ?? collect();
    if ($faqs->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? ($content['intro'] ?? null);
@endphp

<x-section-wrapper
    :section="$section"
    anchor="faq"
    class="py-16 md:py-24"
    :show-circle-motif="true"
    x-data="{ activeFaq: null }"
>
    <div class="max-w-4xl mx-auto flex flex-col gap-12 sm:gap-16">
        {{-- Section Heading --}}
        <div class="text-center">
            <x-heading-block
                :eyebrow="$eyebrow"
                :heading="$heading"
                :subheading="$subheading"
                align="center"
                size="lg"
            />
        </div>

        {{-- Accessible Accordion --}}
        <div class="flex flex-col gap-4">
            @foreach($faqs as $faq)
                <div class="rounded-theme bg-surface border border-border shadow-sm overflow-hidden transition-colors">
                    <h3>
                        <button
                            type="button"
                            id="faq-btn-{{ $faq->id }}"
                            @click="activeFaq = (activeFaq === {{ $faq->id }} ? null : {{ $faq->id }})"
                            :aria-expanded="activeFaq === {{ $faq->id }} ? 'true' : 'false'"
                            aria-controls="faq-panel-{{ $faq->id }}"
                            class="w-full flex items-center justify-between gap-4 p-5 sm:p-6 text-left font-heading text-lg sm:text-xl font-normal text-text hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-inset"
                        >
                            <span>{{ $faq->question }}</span>

                            <span
                                class="shrink-0 transition-transform duration-200"
                                :class="activeFaq === {{ $faq->id }} ? 'rotate-180 text-primary' : 'text-text-muted'"
                            >
                                <x-icon name="heroicon-o-chevron-down" class="w-5 h-5" />
                            </span>
                        </button>
                    </h3>

                    <div
                        id="faq-panel-{{ $faq->id }}"
                        role="region"
                        aria-labelledby="faq-btn-{{ $faq->id }}"
                        x-show="activeFaq === {{ $faq->id }}"
                        x-cloak
                        class="px-5 pb-5 sm:px-6 sm:pb-6 text-sm sm:text-base text-text-muted leading-relaxed font-normal border-t border-border/60 pt-4"
                    >
                        <x-rich-text :content="$faq->answer" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
