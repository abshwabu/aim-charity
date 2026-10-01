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
@endphp

<x-section-wrapper
    :section="$section"
    anchor="volunteer"
    class="py-16 md:py-24"
    :show-circle-motif="true"
>
    <div class="max-w-4xl mx-auto flex flex-col gap-10">
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

        {{-- Application Form --}}
        <div class="rounded-theme bg-surface border border-border p-6 sm:p-10 shadow-sm">
            <form action="#" method="POST" class="flex flex-col gap-6" @submit.prevent>
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Name --}}
                    @if(filled($labels['name'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-name" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['name'] }}
                            </label>
                            <input
                                type="text"
                                id="vol-name"
                                name="name"
                                required
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif

                    {{-- Email --}}
                    @if(filled($labels['email'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-email" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['email'] }}
                            </label>
                            <input
                                type="email"
                                id="vol-email"
                                name="email"
                                required
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Phone --}}
                    @if(filled($labels['phone'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-phone" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['phone'] }}
                            </label>
                            <input
                                type="tel"
                                id="vol-phone"
                                name="phone"
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif

                    {{-- Skills --}}
                    @if(filled($labels['skills'] ?? null))
                        <div class="flex flex-col gap-2">
                            <label for="vol-skills" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                                {{ $labels['skills'] }}
                            </label>
                            <input
                                type="text"
                                id="vol-skills"
                                name="skills"
                                class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                    @endif
                </div>

                {{-- Availability --}}
                @if(filled($labels['availability'] ?? null))
                    <div class="flex flex-col gap-2">
                        <label for="vol-avail" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                            {{ $labels['availability'] }}
                        </label>
                        <input
                            type="text"
                            id="vol-avail"
                            name="availability"
                            class="w-full px-4 py-3 rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                        />
                    </div>
                @endif

                {{-- Message --}}
                @if(filled($labels['message'] ?? null))
                    <div class="flex flex-col gap-2">
                        <label for="vol-message" class="text-xs font-semibold tracking-wider uppercase text-text/80">
                            {{ $labels['message'] }}
                        </label>
                        <textarea
                            id="vol-message"
                            name="message"
                            rows="4"
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
</x-section-wrapper>
