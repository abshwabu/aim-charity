@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $methods = $items ?? collect();
    if ($methods->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? ($content['intro'] ?? null);
    $reassurance = $content['reassurance'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="donate"
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

        {{-- Transparency Reassurance Banner --}}
        @if(filled($reassurance))
            <div class="flex items-start gap-4 p-5 sm:p-6 rounded-theme bg-primary/10 border border-primary/20 text-primary">
                <x-icon name="heroicon-o-shield-check" class="w-6 h-6 shrink-0 mt-0.5 text-accent" />
                <p class="text-sm sm:text-base leading-relaxed text-text font-normal">
                    {{ $reassurance }}
                </p>
            </div>
        @endif

        {{-- Payment Methods Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
            @foreach($methods as $method)
                <div
                    class="flex flex-col justify-between p-6 sm:p-8 rounded-theme bg-surface border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-300"
                    x-data="{ copied: false }"
                >
                    <div class="flex flex-col gap-5">
                        {{-- Method Header: Logo & Bank Label --}}
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                @if(filled($method->logo))
                                    <div class="w-12 h-12 rounded-full p-2 bg-background border border-border flex items-center justify-center shrink-0 overflow-hidden">
                                        <x-image :src="$method->logo" :alt="$method->label" aspect="square" class="w-full h-full object-contain" />
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                        <x-icon name="heroicon-o-banknotes" class="w-6 h-6" />
                                    </div>
                                @endif

                                <h3 class="font-heading text-xl sm:text-2xl font-normal text-text">
                                    {{ $method->label }}
                                </h3>
                            </div>

                            @if(filled($method->qr_image))
                                <div class="w-14 h-14 rounded-theme p-1 bg-background border border-border flex items-center justify-center shrink-0 overflow-hidden">
                                    <x-image :src="$method->qr_image" :alt="$method->label" aspect="square" class="w-full h-full object-contain" />
                                </div>
                            @endif
                        </div>

                        {{-- Account Details Card --}}
                        <div class="p-4 rounded-theme bg-background border border-border/80 flex flex-col gap-2">
                            @if(filled($method->account_name))
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-text-muted gap-1">
                                    <span class="font-medium text-text/90">{{ $method->account_name }}</span>
                                </div>
                            @endif

                            {{-- Account Number with Copy Action --}}
                            @if(filled($method->account_number))
                                <div class="mt-1 flex items-center justify-between gap-2 p-2.5 rounded bg-surface border border-border">
                                    <code class="font-mono text-base sm:text-lg font-bold tracking-wider text-text select-all truncate">
                                        {{ $method->account_number }}
                                    </code>

                                    <button
                                        type="button"
                                        @click="navigator.clipboard.writeText('{{ $method->account_number }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold tracking-wider transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                                        :class="copied ? 'bg-primary text-white' : 'bg-primary/10 text-primary hover:bg-primary/20'"
                                        aria-label="{{ $method->account_number }}"
                                    >
                                        <template x-if="!copied">
                                            <x-icon name="heroicon-o-clipboard-document" class="w-4 h-4" />
                                        </template>
                                        <template x-if="copied">
                                            <x-icon name="heroicon-o-check" class="w-4 h-4" />
                                        </template>
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Instructions --}}
                        @if(filled($method->instructions))
                            <p class="text-xs sm:text-sm text-text-muted leading-relaxed font-normal">
                                {{ $method->instructions }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
