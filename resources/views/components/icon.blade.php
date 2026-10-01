@props([
    'name' => null,
    'class' => 'w-5 h-5 shrink-0',
    'ariaHidden' => true,
])

@php
    use App\Support\Site;

    $iconName = trim($name ?? '');

    $isImagePath = filled($iconName) && (
        str_contains($iconName, '/') ||
        str_starts_with($iconName, 'http') ||
        str_ends_with($iconName, '.png') ||
        str_ends_with($iconName, '.svg') ||
        str_ends_with($iconName, '.webp') ||
        str_ends_with($iconName, '.jpg')
    );

    $svgHtml = null;

    if (filled($iconName) && ! $isImagePath) {
        $normalizedName = str_starts_with($iconName, 'heroicon-')
            ? $iconName
            : "heroicon-o-{$iconName}";

        try {
            $extraAttributes = $ariaHidden ? ['aria-hidden' => 'true'] : [];
            $svgHtml = svg($normalizedName, $class, $extraAttributes)->toHtml();
        } catch (\Throwable) {
            $svgHtml = null;
        }
    }
@endphp

@if(filled($iconName))
    @if($isImagePath)
        <img
            src="{{ Site::imageUrl($iconName) }}"
            alt=""
            @if($ariaHidden) aria-hidden="true" @endif
            loading="lazy"
            {{ $attributes->merge(['class' => "object-contain {$class}"]) }}
        />
    @elseif($svgHtml)
        {!! $svgHtml !!}
    @endif
@endif
