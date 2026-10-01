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
    $subheading = $content['subheading'] ?? ($content['intro'] ?? null);
    $labels = is_array($content['labels'] ?? null) ? $content['labels'] : [];
    $buttonLabel = $content['button_label'] ?? null;
    $privacyNote = $content['privacy_note'] ?? null;
    $showDirectContacts = !empty($content['show_direct_contacts']);
    $showMap = !empty($content['show_map']);

    $contactInfo = $settings?->contact ?? [];
    $hasDirectDetails = filled($contactInfo['email'] ?? null) ||
                        filled($contactInfo['phone'] ?? null) ||
                        filled($contactInfo['address'] ?? null) ||
                        filled($contactInfo['working_hours'] ?? null);
@endphp

<x-section-wrapper
    :section="$section"
    anchor="contact"
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            {{-- Left Column: Direct Contacts & Map if enabled --}}
            @if($showDirectContacts && $hasDirectDetails)
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="p-6 sm:p-8 rounded-theme bg-surface border border-border shadow-sm flex flex-col gap-6">
                        @if(filled($contactInfo['address'] ?? null))
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-theme bg-primary/10 text-primary">
                                    <x-icon name="heroicon-o-map-pin" class="w-5 h-5" />
                                </div>
                                <div class="flex flex-col text-sm text-text-muted leading-relaxed font-normal">
                                    <span>{{ $contactInfo['address'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(filled($contactInfo['phone'] ?? null))
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-theme bg-primary/10 text-primary">
                                    <x-icon name="heroicon-o-phone" class="w-5 h-5" />
                                </div>
                                <a href="tel:{{ preg_replace('/[^\d+]/', '', $contactInfo['phone']) }}" class="text-sm font-medium text-text hover:text-primary transition-colors">
                                    {{ $contactInfo['phone'] }}
                                </a>
                            </div>
                        @endif

                        @if(filled($contactInfo['email'] ?? null))
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-theme bg-primary/10 text-primary">
                                    <x-icon name="heroicon-o-envelope" class="w-5 h-5" />
                                </div>
                                <a href="mailto:{{ $contactInfo['email'] }}" class="text-sm font-medium text-text hover:text-primary transition-colors">
                                    {{ $contactInfo['email'] }}
                                </a>
                            </div>
                        @endif

                        @if(filled($contactInfo['working_hours'] ?? null))
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-theme bg-accent/15 text-accent">
                                    <x-icon name="heroicon-o-clock" class="w-5 h-5" />
                                </div>
                                <span class="text-sm text-text-muted font-normal">
                                    {{ $contactInfo['working_hours'] }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Map Embed if configured --}}
                    @if($showMap && filled($contactInfo['map_embed_url'] ?? null))
                        <div class="overflow-hidden rounded-theme border border-border aspect-video shadow-sm">
                            <iframe
                                src="{{ $contactInfo['map_embed_url'] }}"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full h-full"
                            ></iframe>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Right Column: Inquiries Form --}}
            <div class="{{ ($showDirectContacts && $hasDirectDetails) ? 'lg:col-span-7' : 'lg:col-span-12 max-w-3xl mx-auto w-full' }}">
                <div class="rounded-theme bg-surface border border-border p-6 sm:p-10 shadow-sm">
                    <form action="#" method="POST" class="flex flex-col gap-6" @submit.prevent>
                        @csrf

                        {{-- Name --}}
                        @if(filled($labels['name'] ?? null))
                            <div class="flex flex-col gap-2">
                                <label for="contact-name" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                    {{ $labels['name'] }}
                                </label>
                                <input
                                    type="text"
                                    id="contact-name"
                                    name="name"
                                    required
                                    class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Email --}}
                            @if(filled($labels['email'] ?? null))
                                <div class="flex flex-col gap-2">
                                    <label for="contact-email" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                        {{ $labels['email'] }}
                                    </label>
                                    <input
                                        type="email"
                                        id="contact-email"
                                        name="email"
                                        required
                                        class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                    />
                                </div>
                            @endif

                            {{-- Phone --}}
                            @if(filled($labels['phone'] ?? null))
                                <div class="flex flex-col gap-2">
                                    <label for="contact-phone" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                        {{ $labels['phone'] }}
                                    </label>
                                    <input
                                        type="tel"
                                        id="contact-phone"
                                        name="phone"
                                        class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                    />
                                </div>
                            @endif
                        </div>

                        {{-- Message --}}
                        @if(filled($labels['message'] ?? null))
                            <div class="flex flex-col gap-2">
                                <label for="contact-message" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                    {{ $labels['message'] }}
                                </label>
                                <textarea
                                    id="contact-message"
                                    name="message"
                                    rows="5"
                                    required
                                    class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                ></textarea>
                            </div>
                        @endif

                        {{-- Privacy Note --}}
                        @if(filled($privacyNote))
                            <p class="text-xs text-text-muted leading-relaxed font-normal">
                                {{ $privacyNote }}
                            </p>
                        @endif

                        {{-- Submit Button --}}
                        @if(filled($buttonLabel))
                            <div class="pt-2">
                                <x-button
                                    type="submit"
                                    variant="primary"
                                    size="lg"
                                    class="w-full sm:w-auto"
                                >
                                    {{ $buttonLabel }}
                                </x-button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-section-wrapper>
