@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $memberGroups = $items ?? collect();
    if ($memberGroups->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="member-groups"
    class="py-16 md:py-24"
    :show-circle-motif="true"
    x-data="{ activeModalId: null }"
    @keydown.escape.window="activeModalId = null"
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

        {{-- Member Groups Mosaic Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($memberGroups as $group)
                <div
                    @click="activeModalId = {{ $group->id }}"
                    class="group relative flex flex-col justify-between p-6 sm:p-8 rounded-theme bg-surface border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-300 cursor-pointer text-left focus:outline-none focus:ring-2 focus:ring-primary"
                    tabindex="0"
                    @keydown.enter="activeModalId = {{ $group->id }}"
                    @keydown.space.prevent="activeModalId = {{ $group->id }}"
                    role="button"
                    aria-haspopup="dialog"
                >
                    <div class="flex flex-col gap-4">
                        {{-- Group Header: Logo & Badges --}}
                        <div class="flex items-center justify-between gap-4">
                            @if(filled($group->logo))
                                <div class="w-14 h-14 rounded-full p-2 bg-background border border-border flex items-center justify-center shrink-0 overflow-hidden">
                                    <x-image :src="$group->logo" :alt="$group->name" aspect="square" class="w-full h-full object-contain" />
                                </div>
                            @else
                                <div class="w-14 h-14 rounded-full bg-primary/10 text-primary border border-primary/20 flex items-center justify-center font-bold text-base shrink-0">
                                    {{ mb_substr($group->name, 0, 2) }}
                                </div>
                            @endif

                            @if(filled($group->founded_year))
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-background border border-border text-text-muted">
                                    {{ $group->founded_year }}
                                </span>
                            @endif
                        </div>

                        {{-- Group Name --}}
                        <h3 class="font-heading text-xl sm:text-2xl font-normal text-text group-hover:text-primary transition-colors">
                            {{ $group->name }}
                        </h3>

                        {{-- Focus Area Badge --}}
                        @if(filled($group->focus_area))
                            <div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-theme text-xs font-medium bg-accent/15 text-accent border border-accent/25">
                                    {{ $group->focus_area }}
                                </span>
                            </div>
                        @endif

                        {{-- Short Description --}}
                        @if(filled($group->short_description))
                            <p class="text-sm text-text-muted leading-relaxed line-clamp-3 font-normal">
                                {{ $group->short_description }}
                            </p>
                        @endif
                    </div>

                    {{-- Footer Arrow Icon --}}
                    <div class="mt-6 pt-4 border-t border-border/60 flex items-center justify-between text-xs font-medium text-text-muted group-hover:text-primary transition-colors">
                        @if(filled($group->name))
                            <span class="truncate">{{ $group->name }}</span>
                        @endif
                        <x-icon name="heroicon-o-arrow-up-right" class="w-4 h-4 shrink-0 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                    </div>
                </div>

                {{-- Group Detail Accessible Modal --}}
                <template x-if="activeModalId === {{ $group->id }}">
                    <div
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 lg:p-8"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="modal-group-title-{{ $group->id }}"
                    >
                        {{-- Backdrop --}}
                        <div
                            class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                            @click="activeModalId = null"
                            aria-hidden="true"
                        ></div>

                        {{-- Modal Content Panel --}}
                        <div
                            class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-theme bg-surface border border-border shadow-2xl p-6 sm:p-8 flex flex-col gap-6"
                            @click.stop
                        >
                            {{-- Close Action --}}
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    @if(filled($group->logo))
                                        <div class="w-12 h-12 rounded-full p-1.5 bg-background border border-border flex items-center justify-center shrink-0 overflow-hidden">
                                            <x-image :src="$group->logo" :alt="$group->name" aspect="square" class="w-full h-full object-contain" />
                                        </div>
                                    @endif
                                    @if(filled($group->focus_area))
                                        <span class="px-3 py-1 rounded-theme text-xs font-semibold bg-accent/15 text-accent border border-accent/25">
                                            {{ $group->focus_area }}
                                        </span>
                                    @endif
                                </div>

                                <button
                                    type="button"
                                    @click="activeModalId = null"
                                    class="p-2 rounded-full text-text-muted hover:text-text hover:bg-black/5 transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                                    aria-label="Close dialog"
                                >
                                    <x-icon name="heroicon-o-x-mark" class="w-5 h-5" />
                                </button>
                            </div>

                            {{-- Group Photo if present --}}
                            @if(filled($group->photo))
                                <div class="overflow-hidden rounded-theme border border-border aspect-video">
                                    <x-image :src="$group->photo" :alt="$group->name" aspect="video" class="w-full h-full object-cover" />
                                </div>
                            @endif

                            {{-- Heading & Year --}}
                            <div class="flex flex-col gap-2">
                                <h3 id="modal-group-title-{{ $group->id }}" class="font-heading text-2xl sm:text-3xl font-normal text-text">
                                    {{ $group->name }}
                                </h3>
                                @if(filled($group->founded_year))
                                    <span class="text-xs text-text-muted font-medium">
                                        {{ $group->founded_year }}
                                    </span>
                                @endif
                            </div>

                            {{-- Full Description --}}
                            @if(filled($group->long_description))
                                <div class="prose max-w-none text-text">
                                    <x-rich-text :content="$group->long_description" />
                                </div>
                            @elseif(filled($group->short_description))
                                <p class="text-base text-text-muted leading-relaxed font-normal">
                                    {{ $group->short_description }}
                                </p>
                            @endif

                            {{-- External Links --}}
                            @if(filled($group->website_url))
                                <div class="pt-4 border-t border-border flex items-center justify-between">
                                    <x-button
                                        variant="outline"
                                        size="sm"
                                        :href="$group->website_url"
                                        :open-in-new-tab="true"
                                        icon="heroicon-o-globe-alt"
                                    >
                                        {{ $group->website_url }}
                                    </x-button>
                                </div>
                            @endif
                        </div>
                    </div>
                </template>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
