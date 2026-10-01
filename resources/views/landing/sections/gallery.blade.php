@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $galleryItems = $items ?? collect();
    if ($galleryItems->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="gallery"
    class="py-16 md:py-24"
    :show-circle-motif="false"
    x-data="{ activeImage: null, activeCaption: null, activeAlt: null }"
    @keydown.escape.window="activeImage = null"
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

        {{-- Gallery Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($galleryItems as $item)
                @php
                    $imageUrl = \App\Support\Site::imageUrl($item->image);
                @endphp
                <div
                    @click="activeImage = '{{ $imageUrl }}'; activeCaption = '{{ addslashes($item->caption ?? '') }}'; activeAlt = '{{ addslashes($item->alt_text ?? '') }}'"
                    class="group relative overflow-hidden rounded-theme bg-surface border border-border shadow-sm cursor-pointer aspect-video sm:aspect-[4/3] focus:outline-none focus:ring-2 focus:ring-primary"
                    tabindex="0"
                    role="button"
                    aria-haspopup="dialog"
                    @keydown.enter="activeImage = '{{ $imageUrl }}'; activeCaption = '{{ addslashes($item->caption ?? '') }}'; activeAlt = '{{ addslashes($item->alt_text ?? '') }}'"
                    @keydown.space.prevent="activeImage = '{{ $imageUrl }}'; activeCaption = '{{ addslashes($item->caption ?? '') }}'; activeAlt = '{{ addslashes($item->alt_text ?? '') }}'"
                >
                    <x-image
                        :src="$item->image"
                        :alt="$item->alt_text ?? $item->caption ?? ''"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />

                    {{-- Hover Caption Overlay --}}
                    @if(filled($item->caption))
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-6 flex flex-col justify-end">
                            <p class="text-sm font-medium text-white line-clamp-2">
                                {{ $item->caption }}
                            </p>
                            @if($item->memberGroup)
                                <span class="text-xs text-accent mt-1">
                                    {{ $item->memberGroup->name }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Accessible Lightbox Modal --}}
    <template x-if="activeImage">
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-8"
            role="dialog"
            aria-modal="true"
            aria-label="Image preview lightbox"
        >
            {{-- Backdrop --}}
            <div
                class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"
                @click="activeImage = null"
                aria-hidden="true"
            ></div>

            {{-- Image Panel --}}
            <div
                class="relative z-10 max-w-5xl max-h-[90vh] flex flex-col items-center gap-4"
                @click.stop
            >
                <button
                    type="button"
                    @click="activeImage = null"
                    class="self-end p-2 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white"
                    aria-label="Close dialog"
                >
                    <x-icon name="heroicon-o-x-mark" class="w-6 h-6" />
                </button>

                <div class="overflow-hidden rounded-theme border border-white/20 shadow-2xl bg-black">
                    <img
                        :src="activeImage"
                        :alt="activeAlt || ''"
                        class="max-h-[75vh] w-auto object-contain"
                    />
                </div>

                <template x-if="activeCaption">
                    <p class="text-sm text-white/90 text-center max-w-2xl px-4 py-2 rounded-theme bg-black/60 backdrop-blur-sm" x-text="activeCaption"></p>
                </template>
            </div>
        </div>
    </template>
</x-section-wrapper>
