@props([
    'eyebrow' => null,
    'heading' => null,
    'subheading' => null,
    'align' => 'left',
    'size' => 'md',
    'as' => 'h2',
    'badge' => null,
    'class' => '',
])

@php
    $alignClasses = match ($align) {
        'center' => 'text-center mx-auto items-center',
        'right' => 'text-right ml-auto items-end',
        default => 'text-left items-start',
    };

    $headingSizeClasses = match ($size) {
        'sm' => 'text-2xl sm:text-3xl tracking-tight',
        'lg' => 'text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-[1.1]',
        'xl' => 'text-5xl sm:text-6xl lg:text-7xl tracking-tight leading-[1.08]',
        default => 'text-3xl sm:text-4xl lg:text-5xl tracking-tight leading-tight', // md
    };

    $subheadingSizeClasses = match ($size) {
        'sm' => 'text-sm sm:text-base',
        'lg' => 'text-lg sm:text-xl leading-relaxed',
        'xl' => 'text-xl sm:text-2xl leading-relaxed',
        default => 'text-base sm:text-lg leading-relaxed', // md
    };

    $hasContent = filled($eyebrow) || filled($heading) || filled($subheading) || ! $slot->isEmpty();
@endphp

@if($hasContent)
    <div {{ $attributes->merge(['class' => "flex flex-col max-w-3xl {$alignClasses} {$class}"]) }}>
        {{-- Optional Eyebrow / Category Chip --}}
        @if(filled($eyebrow) || filled($badge))
            <div class="inline-flex items-center gap-2 mb-3.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase bg-accent/15 text-accent border border-accent/25">
                    @if(filled($badge))
                        <span class="w-1.5 h-1.5 rounded-full bg-accent" aria-hidden="true"></span>
                    @endif
                    {{ $eyebrow ?? $badge }}
                </span>
            </div>
        @endif

        {{-- Editorial Heading with Serif Font --}}
        @if(filled($heading) || ! $slot->isEmpty())
            <{{ $as }} class="font-heading font-normal {{ $headingSizeClasses }}">
                {{ $heading ?? $slot }}
            </{{ $as }}>
        @endif

        {{-- Subheading / Lead Paragraph --}}
        @if(filled($subheading))
            <p class="mt-4 font-normal opacity-85 {{ $subheadingSizeClasses }}">
                {{ $subheading }}
            </p>
        @endif
    </div>
@endif
