@props([
    'settings' => null,
    'hero' => null,
])

@php
    $branding = $settings?->branding ?? [];
    $contact = $settings?->contact ?? [];
    $content = $hero?->content ?? [];

    $siteName = $branding['site_name'] ?? 'Aim Charity';
    $tagline = $branding['tagline'] ?? 'A Coalition of Community Organizations in Ethiopia';
    $statusBadge = $content['eyebrow'] ?? 'Platform Initialized & Active';
    $heroTitle = $content['heading'] ?? 'Empowering Communities, Transforming Lives Together';
    $heroSubtitle = $content['subheading'] ?? 'A coalition of grassroots community organizations in Ethiopia united to deliver mutual aid, relief, and sustainable impact to families and communities in need.';
    $adminButtonText = $content['buttons'][0]['label'] ?? 'Access Admin Panel';
    $adminButtonUrl = $content['buttons'][0]['url'] ?? url('/admin');
    $contactEmail = $contact['email'] ?? 'contact@aimcharity.org';
@endphp

<header class="w-full border-b border-slate-200/80 bg-white/80 backdrop-blur-md sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-500/20">
                <svg class="h-5 w-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
            </div>
            <div>
                <span class="text-base font-bold text-slate-900 tracking-tight block leading-tight">{{ $siteName }}</span>
                <span class="text-[11px] text-slate-500 font-medium tracking-wide block">{{ $tagline }}</span>
            </div>
        </div>

        <nav class="flex items-center gap-4">
            <a href="{{ $adminButtonUrl }}"
               class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                {{ $adminButtonText }}
                <svg class="ml-1.5 h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                </svg>
            </a>
        </nav>
    </div>
</header>

<main class="flex-1 flex flex-col justify-center py-16 sm:py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data="{ expanded: false }">
        @if ($statusBadge)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20 mb-8">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $statusBadge }}</span>
            </div>
        @endif

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
            {{ $heroTitle }}
        </h1>

        <p class="mt-6 text-lg sm:text-xl text-slate-600 leading-relaxed max-w-2xl mx-auto">
            {{ $heroSubtitle }}
        </p>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ $adminButtonUrl }}"
               class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                {{ $adminButtonText }}
            </a>
            <button type="button"
                    @click="expanded = !expanded"
                    class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                <span x-text="expanded ? 'Hide Information' : 'About the Coalition'"></span>
            </button>
        </div>

        <div x-show="expanded"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mt-10 max-w-xl mx-auto p-6 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-left">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-2">Foundation Overview</h3>
            <p class="text-sm text-slate-600 mb-3">
                All landing page elements, sections, media, partner groups, and campaigns are dynamically managed through the Filament Administration Panel.
            </p>
            @if ($contactEmail)
                <div class="text-xs text-slate-500">
                    Contact: <a href="mailto:{{ $contactEmail }}" class="text-emerald-600 underline font-medium">{{ $contactEmail }}</a>
                </div>
            @endif
        </div>
    </div>
</main>

<footer class="border-t border-slate-200 bg-white py-6">
    <div class="max-w-6xl mx-auto px-4 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
    </div>
</footer>
