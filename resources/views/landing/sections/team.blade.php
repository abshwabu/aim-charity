@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $teamMembers = $items ?? collect();
    if ($teamMembers->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? ($content['intro'] ?? null);
@endphp

<x-section-wrapper
    :section="$section"
    anchor="team"
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

        {{-- Team Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($teamMembers as $member)
                <div class="flex flex-col rounded-theme bg-surface border border-border shadow-sm overflow-hidden hover:shadow-md hover:border-primary/40 transition-all duration-300">
                    {{-- Photo --}}
                    @if(filled($member->photo))
                        <div class="aspect-[4/5] w-full overflow-hidden bg-background">
                            <x-image
                                :src="$member->photo"
                                :alt="$member->name"
                                aspect="portrait"
                                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                            />
                        </div>
                    @endif

                    <div class="p-6 flex flex-col gap-2">
                        <h3 class="font-heading text-lg sm:text-xl font-normal text-text">
                            {{ $member->name }}
                        </h3>

                        @if(filled($member->role))
                            <span class="text-xs font-semibold uppercase tracking-wider text-accent">
                                {{ $member->role }}
                            </span>
                        @endif

                        @if($member->memberGroup)
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-primary/10 text-primary border border-primary/20">
                                    {{ $member->memberGroup->name }}
                                </span>
                            </div>
                        @endif

                        @if(filled($member->bio))
                            <p class="text-xs text-text-muted leading-relaxed font-normal mt-2 line-clamp-3">
                                {{ $member->bio }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
