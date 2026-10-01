@props([
    'src' => null,
    'alt' => '',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'decoding' => 'async',
    'aspect' => null,
    'rounded' => 'rounded-theme',
    'fit' => 'cover',
    'class' => '',
])

@php
    use App\Support\Site;

    $url = filled($src) ? Site::imageUrl($src) : null;

    $aspectClasses = match ($aspect) {
        'square', '1:1' => 'aspect-square',
        'video', '16:9' => 'aspect-video',
        'portrait', '4:5' => 'aspect-[4/5]',
        'story', '9:16' => 'aspect-[9/16]',
        'banner', '21:9' => 'aspect-[21/9]',
        default => '',
    };

    $fitClasses = match ($fit) {
        'contain' => 'object-contain',
        'fill' => 'object-fill',
        'none' => 'object-none',
        default => 'object-cover',
    };
@endphp

@if(filled($url))
    <img
        src="{{ $url }}"
        alt="{{ $alt }}"
        @if(filled($width)) width="{{ $width }}" @endif
        @if(filled($height)) height="{{ $height }}" @endif
        loading="{{ $loading }}"
        decoding="{{ $decoding }}"
        {{ $attributes->merge(['class' => "block w-full max-w-full {$fitClasses} {$aspectClasses} {$rounded} {$class}"]) }}
    />
@endif
