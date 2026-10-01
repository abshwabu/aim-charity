@props([
    'section' => null,
    'id' => null,
    'anchor' => null,
    'backgroundColor' => null,
    'backgroundImage' => null,
    'backgroundOverlay' => null,
    'textTheme' => null,
    'paddingSize' => null,
    'showCircleMotif' => false,
    'containerClass' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
    'class' => '',
])

@php
    use App\Support\ColorHelper;
    use App\Support\Site;

    $style = is_array($section?->style) ? $section->style : [];

    $resolvedAnchor = $anchor ?? $id ?? $section?->anchor ?? $section?->key;
    $resolvedBgColor = $backgroundColor ?? $style['background_color'] ?? null;
    $resolvedBgImage = $backgroundImage ?? $style['background_image'] ?? null;
    $resolvedOverlay = $backgroundOverlay ?? $style['background_overlay'] ?? null;
    $resolvedTextTheme = $textTheme ?? $style['text_theme'] ?? null;
    $resolvedPadding = $paddingSize ?? $style['padding_size'] ?? $style['vertical_padding'] ?? 'default';

    // Auto-detect dark/light theme if not explicitly forced
    if (empty($resolvedTextTheme) && filled($resolvedBgColor)) {
        $resolvedTextTheme = ColorHelper::isDark($resolvedBgColor) ? 'dark' : 'light';
    }

    $isDarkTheme = ($resolvedTextTheme === 'dark');

    $paddingClasses = match (strtolower((string) $resolvedPadding)) {
        'none' => 'py-0',
        's', 'sm' => 'py-10 md:py-14',
        'l', 'lg' => 'py-20 md:py-32',
        'xl' => 'py-24 md:py-40',
        default => 'py-16 md:py-24', // m / default
    };

    $themeClasses = $isDarkTheme
        ? 'text-white [&_.text-muted]:text-white/75 [&_.text-body]:text-white/90'
        : 'text-text [&_.text-muted]:text-text-muted [&_.text-body]:text-text';

    $bgImageUrl = filled($resolvedBgImage) ? Site::imageUrl($resolvedBgImage) : null;

    // Overlay opacity conversion
    $overlayOpacity = 0.5;
    if (filled($resolvedOverlay)) {
        $numericOverlay = (float) $resolvedOverlay;
        $overlayOpacity = $numericOverlay > 1 ? ($numericOverlay / 100) : $numericOverlay;
    }
@endphp

<section
    @if(filled($resolvedAnchor)) id="{{ $resolvedAnchor }}" @endif
    {{ $attributes->merge(['class' => "relative overflow-hidden {$paddingClasses} {$themeClasses} {$class}"]) }}
    @if(filled($resolvedBgColor)) style="background-color: {{ $resolvedBgColor }};" @endif
>
    {{-- Background image layer if present --}}
    @if($bgImageUrl)
        <div
            class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none"
            style="background-image: url('{{ $bgImageUrl }}');"
            aria-hidden="true"
        ></div>
        {{-- Background overlay --}}
        <div
            class="absolute inset-0 pointer-events-none"
            style="background-color: {{ $resolvedBgColor ?? ($isDarkTheme ? '#143527' : '#fbf9f5') }}; opacity: {{ $overlayOpacity }};"
            aria-hidden="true"
        ></div>
    @endif

    {{-- "Many groups, one circle" organic motif --}}
    @if($showCircleMotif)
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="motif-circle w-[600px] h-[600px] -top-48 -right-32 border border-current opacity-[0.05]"></div>
            <div class="motif-circle w-[450px] h-[450px] -bottom-36 -left-20 border border-current opacity-[0.04]"></div>
            <div class="motif-circle w-[300px] h-[300px] top-1/2 left-1/3 -translate-y-1/2 border border-accent opacity-[0.06]"></div>
        </div>
    @endif

    <div class="relative z-10 {{ $containerClass }}">
        {{ $slot }}
    </div>
</section>
