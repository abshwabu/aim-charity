@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'left',
    'openInNewTab' => false,
    'disabled' => false,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none active:scale-[0.98] select-none';

    $sizeClasses = match ($size) {
        'sm' => 'px-3.5 py-1.5 text-xs gap-1.5',
        'lg' => 'px-6 py-3.5 text-base gap-2.5 shadow-sm',
        default => 'px-5 py-2.5 text-sm gap-2 shadow-sm', // md
    };

    $variantClasses = match ($variant) {
        'secondary' => 'bg-secondary text-white hover:opacity-95 focus-visible:ring-secondary',
        'outline' => 'border-2 border-primary text-primary hover:bg-primary hover:text-white focus-visible:ring-primary',
        'outline-white' => 'border-2 border-white/80 text-white hover:bg-white hover:text-text focus-visible:ring-white',
        'accent' => 'bg-accent text-white hover:opacity-95 focus-visible:ring-accent shadow-amber-900/10',
        'ghost' => 'text-text hover:bg-black/5 focus-visible:ring-primary shadow-none',
        'ghost-white' => 'text-white hover:bg-white/10 focus-visible:ring-white shadow-none',
        default => 'bg-primary text-white hover:bg-primary-hover focus-visible:ring-primary shadow-emerald-900/15', // primary
    };

    $radiusClass = 'rounded-theme';
    $combinedClasses = "{$baseClasses} {$sizeClasses} {$variantClasses} {$radiusClass} {$class}";
@endphp

@if(filled($href))
    <a
        href="{{ $href }}"
        @if($openInNewTab) target="_blank" rel="noopener noreferrer" @endif
        {{ $attributes->merge(['class' => $combinedClasses]) }}
    >
        @if($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="w-4 h-4 shrink-0" />
        @endif

        <span>{{ $slot }}</span>

        @if($icon && $iconPosition === 'right')
            <x-icon :name="$icon" class="w-4 h-4 shrink-0" />
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => $combinedClasses]) }}
    >
        @if($icon && $iconPosition === 'left')
            <x-icon :name="$icon" class="w-4 h-4 shrink-0" />
        @endif

        <span>{{ $slot }}</span>

        @if($icon && $iconPosition === 'right')
            <x-icon :name="$icon" class="w-4 h-4 shrink-0" />
        @endif
    </button>
@endif
