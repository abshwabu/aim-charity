@php
    use App\Support\Site;

    $settings = Site::settings();
    $logoDark = $settings['branding']['logo_dark'] ?? null;
    $logoLight = $settings['branding']['logo_light'] ?? null;
    $siteName = $settings['branding']['site_name'] ?? 'Aim Charity';
    $logoUrlDark = filled($logoDark) ? Site::imageUrl($logoDark) : null;
    $logoUrlLight = filled($logoLight) ? Site::imageUrl($logoLight) : null;
@endphp

<div class="flex items-center gap-3">
    @if(filled($logoUrlDark) || filled($logoUrlLight))
        <div class="flex items-center">
            @if(filled($logoUrlDark))
                <img src="{{ $logoUrlDark }}" alt="{{ $siteName }}" class="h-8 w-auto max-w-[200px] object-contain fi-logo-light dark:hidden" />
            @endif
            @if(filled($logoUrlLight))
                <img src="{{ $logoUrlLight }}" alt="{{ $siteName }}" class="h-8 w-auto max-w-[200px] object-contain fi-logo-dark hidden dark:block" />
            @elseif(filled($logoUrlDark))
                <img src="{{ $logoUrlDark }}" alt="{{ $siteName }}" class="h-8 w-auto max-w-[200px] object-contain fi-logo-dark hidden dark:block" />
            @endif
        </div>
    @else
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-500/20">
            <svg class="h-5 w-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
            </svg>
        </div>
        <div class="flex flex-col text-left">
            <span class="text-base font-bold tracking-tight text-gray-900 dark:text-white">{{ $siteName }}</span>
            <span class="text-[10px] font-medium uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Admin Portal</span>
        </div>
    @endif
</div>
