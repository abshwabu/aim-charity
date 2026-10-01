@props([
    'section' => null,
    'settings' => null,
    'items' => null,
    'allItems' => null,
])

@php
    $stats = $items ?? collect();
    if ($stats->isEmpty()) {
        return;
    }

    $content = is_array($section?->content) ? $section->content : [];
    $eyebrow = $content['eyebrow'] ?? null;
    $heading = $content['heading'] ?? null;
    $subheading = $content['subheading'] ?? null;
@endphp

<x-section-wrapper
    :section="$section"
    anchor="impact-stats"
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

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($stats as $stat)
                @php
                    $numericVal = (int) preg_replace('/[^\d]/', '', (string) $stat->value);
                    $hasNumeric = $numericVal > 0;
                @endphp
                <div
                    class="flex flex-col gap-4 p-6 sm:p-8 rounded-theme bg-surface border border-border shadow-sm hover:shadow-md hover:border-primary/40 transition-all duration-300"
                    @if($hasNumeric)
                        x-data="{
                            current: 0,
                            target: {{ $numericVal }},
                            init() {
                                let start = 0;
                                let duration = 1500;
                                let stepTime = 25;
                                let steps = duration / stepTime;
                                let inc = this.target / steps;
                                let obs = new IntersectionObserver((entries) => {
                                    if (entries[0].isIntersecting) {
                                        let timer = setInterval(() => {
                                            start += inc;
                                            if (start >= this.target) {
                                                this.current = this.target;
                                                clearInterval(timer);
                                            } else {
                                                this.current = Math.floor(start);
                                            }
                                        }, stepTime);
                                        obs.disconnect();
                                    }
                                }, { threshold: 0.2 });
                                obs.observe(this.$el);
                            }
                        }"
                    @endif
                >
                    {{-- Icon Badge --}}
                    @if(filled($stat->icon))
                        <div class="flex h-12 w-12 items-center justify-center rounded-theme bg-accent/15 text-accent">
                            <x-icon :name="$stat->icon" class="w-6 h-6" />
                        </div>
                    @endif

                    {{-- Number Counter --}}
                    <div class="flex items-baseline gap-1 font-heading text-4xl sm:text-5xl font-bold tracking-tight text-text">
                        @if($hasNumeric)
                            <span x-text="current.toLocaleString()">{{ $stat->value }}</span>
                        @else
                            <span>{{ $stat->value }}</span>
                        @endif

                        @if(filled($stat->suffix))
                            <span class="text-accent text-3xl sm:text-4xl font-normal">{{ $stat->suffix }}</span>
                        @endif
                    </div>

                    {{-- Description Label --}}
                    @if(filled($stat->label))
                        <p class="text-sm text-text-muted leading-relaxed font-normal">
                            {{ $stat->label }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-section-wrapper>
