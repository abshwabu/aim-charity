@props([
    'settings' => null,
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogImage' => null,
])

@php
    use App\Support\ColorHelper;
    use App\Support\Site;

    $settings = $settings ?? Site::settings();

    $branding = $settings->branding ?? [];
    $theme = $settings->theme ?? [];
    $contact = $settings->contact ?? [];
    $social = $settings->social ?? [];
    $navigation = $settings->navigation ?? [];
    $seo = $settings->seo ?? [];
    $footer = $settings->footer ?? [];

    $siteName = $branding['site_name'] ?? config('app.name', 'Aim Charity');
    $tagline = $branding['tagline'] ?? null;

    // Theme Variables
    $primary = $theme['primary'] ?? '#1b4332';
    $primaryHover = ColorHelper::adjustBrightness($primary, -12);
    $secondary = $theme['secondary'] ?? '#2d6a4f';
    $accent = $theme['accent'] ?? '#d97706';
    $background = $theme['background'] ?? '#fbf9f5';
    $surface = $theme['surface'] ?? '#ffffff';
    $text = $theme['text'] ?? '#1c1917';
    $textMuted = ColorHelper::adjustBrightness($text, 35);
    $border = ColorHelper::adjustBrightness($background, -7);
    $radiusStyle = $theme['radius_style'] ?? 'rounded-2xl';
    $radiusValue = ColorHelper::radiusValue($radiusStyle);
    $headingFont = $theme['heading_font'] ?? 'Fraunces';
    $bodyFont = $theme['body_font'] ?? 'Plus Jakarta Sans';

    // Google Fonts URL
    $googleFontsUrl = ColorHelper::googleFontsUrl($headingFont, $bodyFont);

    // SEO Data
    $pageTitle = $title ?? $seo['meta_title'] ?? $siteName;
    $metaDescription = $description ?? $seo['meta_description'] ?? $tagline;
    $canonicalUrl = $canonical ?? url()->current();
    $twitterHandle = $seo['twitter_handle'] ?? null;
    $resolvedOgImage = $ogImage ?? (filled($seo['og_image'] ?? null) ? Site::imageUrl($seo['og_image']) : null);
    $faviconUrl = filled($branding['favicon'] ?? null) ? Site::imageUrl($branding['favicon']) : asset('favicon.ico');

    // Logo Resolution
    $logoLight = filled($branding['logo_light'] ?? null) ? Site::imageUrl($branding['logo_light']) : null;
    $footerLogo = filled($branding['footer_logo'] ?? null)
        ? Site::imageUrl($branding['footer_logo'])
        : ($logoLight ?? (filled($branding['logo_dark'] ?? null) ? Site::imageUrl($branding['logo_dark']) : null));

    // Navigation Items & CTA
    $navItems = is_array($navigation) ? ($navigation['items'] ?? $navigation) : [];
    if (! is_array($navItems)) {
        $navItems = [];
    }

    // Filter out associative CTA keys if navigation was stored flat
    $menuLinks = array_filter($navItems, fn ($item) => is_array($item) && isset($item['label']));

    // Extract Header CTA
    $ctaLabel = $navigation['cta_label'] ?? null;
    $ctaUrl = $navigation['cta_url'] ?? null;
    $ctaTarget = $navigation['cta_target'] ?? 'url';
    if ($ctaTarget === 'section' && filled($navigation['cta_section_key'] ?? null)) {
        $ctaUrl = '#' . $navigation['cta_section_key'];
    }

    // Copyright resolution with {year} token
    $copyrightRaw = $footer['copyright_text'] ?? null;
    $copyrightResolved = filled($copyrightRaw)
        ? str_replace('{year}', (string) date('Y'), $copyrightRaw)
        : null;

    $legalLinks = is_array($footer['legal_links'] ?? null) ? $footer['legal_links'] : [];
    $aboutBlurb = $footer['about_blurb'] ?? null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $pageTitle }}</title>

    {{-- SEO Meta Tags --}}
    @if(filled($metaDescription))
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    @if(filled($metaDescription))
        <meta property="og:description" content="{{ $metaDescription }}">
    @endif
    <meta property="og:site_name" content="{{ $siteName }}">
    @if(filled($resolvedOgImage))
        <meta property="og:image" content="{{ $resolvedOgImage }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    @if(filled($metaDescription))
        <meta name="twitter:description" content="{{ $metaDescription }}">
    @endif
    @if(filled($twitterHandle))
        <meta name="twitter:site" content="{{ $twitterHandle }}">
    @endif
    @if(filled($resolvedOgImage))
        <meta name="twitter:image" content="{{ $resolvedOgImage }}">
    @endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ $faviconUrl }}">

    {{-- Google Fonts --}}
    @if($googleFontsUrl)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ $googleFontsUrl }}" rel="stylesheet">
    @endif

    {{-- Injected Dynamic Theme CSS Variables --}}
    <style>
        :root {
            --color-primary: {{ $primary }};
            --color-primary-hover: {{ $primaryHover }};
            --color-secondary: {{ $secondary }};
            --color-accent: {{ $accent }};
            --color-background: {{ $background }};
            --color-surface: {{ $surface }};
            --color-text: {{ $text }};
            --color-text-muted: {{ $textMuted }};
            --color-border: {{ $border }};
            --radius: {{ $radiusValue }};
            --font-heading: '{{ $headingFont }}', Georgia, serif;
            --font-body: '{{ $bodyFont }}', ui-sans-serif, system-ui, sans-serif;
        }
    </style>

    {{-- Compiled Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Advanced Analytics Snippet (raw, trusted admin-only) --}}
    @if(filled($seo['analytics_snippet'] ?? null))
        {!! $seo['analytics_snippet'] !!}
    @endif
</head>
<body
    class="flex min-h-full flex-col font-sans selection:bg-accent selection:text-white"
    x-data="{ mobileMenuOpen: false }"
    @keydown.escape.window="mobileMenuOpen = false"
>
    {{-- Accessibility Skip Link --}}
    <a
        href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2.5 focus:bg-primary focus:text-white focus:rounded-theme focus:shadow-xl focus:ring-2 focus:ring-accent focus:outline-none text-sm font-semibold"
    >
        Skip to main content
    </a>

    {{-- Sticky Responsive Header --}}
    <header class="sticky top-0 z-40 w-full border-b border-border/80 bg-surface/90 backdrop-blur-md transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between gap-4">
                {{-- Logo / Coalition Brand --}}
                <a
                    href="{{ url('/') }}"
                    class="group inline-flex items-center gap-3.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-theme p-1 -m-1"
                >
                    @if($logoLight)
                        <img
                            src="{{ $logoLight }}"
                            alt="{{ $siteName }}"
                            class="h-10 w-auto object-contain max-w-[200px]"
                        />
                    @else
                        {{-- "Many groups, one circle" Monogram Symbol --}}
                        <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary text-white shadow-sm ring-4 ring-primary/10">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <circle cx="12" cy="12" r="8" opacity="0.6" stroke="currentColor"/>
                                <circle cx="9" cy="11" r="4.5" stroke="currentColor"/>
                                <circle cx="15" cy="11" r="4.5" stroke="currentColor"/>
                            </svg>
                        </div>
                    @endif

                    @if(filled($siteName))
                        <div class="flex flex-col">
                            <span class="font-heading text-xl font-bold tracking-tight text-primary leading-tight">
                                {{ $siteName }}
                            </span>
                            @if(filled($tagline))
                                <span class="hidden sm:block text-[11px] font-medium tracking-wide text-text-muted truncate max-w-[260px]">
                                    {{ $tagline }}
                                </span>
                            @endif
                        </div>
                    @endif
                </a>

                {{-- Desktop Navigation --}}
                @if(count($menuLinks) > 0)
                    <nav class="hidden md:flex items-center gap-1 lg:gap-2" aria-label="Main Navigation">
                        @foreach($menuLinks as $link)
                            @php
                                $target = $link['target'] ?? 'section';
                                $href = $target === 'section'
                                    ? '#' . ($link['section_key'] ?? '')
                                    : ($link['url'] ?? '#');
                                $openNewTab = !empty($link['open_in_new_tab']);
                            @endphp
                            <a
                                href="{{ $href }}"
                                @if($openNewTab) target="_blank" rel="noopener noreferrer" @endif
                                class="px-3.5 py-2 text-sm font-medium text-text/85 hover:text-primary transition-colors duration-150 rounded-theme focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                @endif

                {{-- Right Actions (Header CTA + Mobile Menu Button) --}}
                <div class="flex items-center gap-3">
                    @if(filled($ctaLabel) && filled($ctaUrl))
                        <div class="hidden sm:inline-flex">
                            <x-button
                                variant="primary"
                                size="sm"
                                :href="$ctaUrl"
                                :open-in-new-tab="!empty($navigation['cta_new_tab'])"
                            >
                                {{ $ctaLabel }}
                            </x-button>
                        </div>
                    @endif

                    {{-- Mobile menu button --}}
                    @if(count($menuLinks) > 0 || (filled($ctaLabel) && filled($ctaUrl)))
                        <button
                            type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="md:hidden inline-flex items-center justify-center p-2 rounded-theme text-text/80 hover:text-primary hover:bg-black/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                            :aria-expanded="mobileMenuOpen.toString()"
                            aria-controls="mobile-navigation"
                            aria-label="Toggle navigation menu"
                        >
                            <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Mobile Drawer Navigation --}}
        @if(count($menuLinks) > 0 || (filled($ctaLabel) && filled($ctaUrl)))
            <div
                id="mobile-navigation"
                x-show="mobileMenuOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="md:hidden border-b border-border bg-surface px-4 pt-3 pb-6 shadow-xl"
            >
                <nav class="flex flex-col gap-1.5" aria-label="Mobile Navigation">
                    @foreach($menuLinks as $link)
                        @php
                            $target = $link['target'] ?? 'section';
                            $href = $target === 'section'
                                ? '#' . ($link['section_key'] ?? '')
                                : ($link['url'] ?? '#');
                            $openNewTab = !empty($link['open_in_new_tab']);
                        @endphp
                        <a
                            href="{{ $href }}"
                            @click="mobileMenuOpen = false"
                            @if($openNewTab) target="_blank" rel="noopener noreferrer" @endif
                            class="px-3.5 py-2.5 rounded-theme text-base font-medium text-text hover:bg-black/5 hover:text-primary transition-colors"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach

                    @if(filled($ctaLabel) && filled($ctaUrl))
                        <div class="mt-4 pt-4 border-t border-border">
                            <x-button
                                variant="primary"
                                size="md"
                                :href="$ctaUrl"
                                class="w-full text-center justify-center"
                                @click="mobileMenuOpen = false"
                            >
                                {{ $ctaLabel }}
                            </x-button>
                        </div>
                    @endif
                </nav>
            </div>
        @endif
    </header>

    {{-- Main Content Landmark --}}
    <main id="main-content" class="flex-1 focus:outline-none" tabindex="-1">
        {{ $slot }}
    </main>

    {{-- Global Site Footer --}}
    <footer class="relative overflow-hidden bg-surface border-t border-border/80 text-text pt-16 pb-12" aria-labelledby="footer-heading">
        <h2 id="footer-heading" class="sr-only">Footer</h2>

        {{-- Background "Many groups, one circle" arch watermark motif --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="motif-arch w-[700px] h-[350px] -bottom-24 -right-24 border border-primary opacity-[0.04]"></div>
            <div class="motif-circle w-[400px] h-[400px] -top-32 -left-20 border border-accent opacity-[0.03]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-12 pb-14 border-b border-border/70">
                {{-- Column 1: Organization Branding, Blurb & Social --}}
                <div class="lg:col-span-5 flex flex-col gap-5">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 rounded-theme focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary w-fit">
                        @if($footerLogo)
                            <img src="{{ $footerLogo }}" alt="{{ $siteName }}" class="h-10 w-auto object-contain max-w-[200px]" />
                        @else
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary text-white">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8" opacity="0.6"/>
                                    <circle cx="9" cy="11" r="4.5"/>
                                    <circle cx="15" cy="11" r="4.5"/>
                                </svg>
                            </div>
                        @endif

                        @if(filled($siteName))
                            <span class="font-heading text-xl font-bold tracking-tight text-primary">
                                {{ $siteName }}
                            </span>
                        @endif
                    </a>

                    @if(filled($aboutBlurb))
                        <p class="text-sm leading-relaxed text-text-muted max-w-md">
                            {{ $aboutBlurb }}
                        </p>
                    @endif

                    {{-- Social Channels Repeater --}}
                    @if(is_array($social) && count($social) > 0)
                        <div class="flex flex-wrap items-center gap-2.5 pt-2" aria-label="Social Media Channels">
                            @foreach($social as $item)
                                @if(filled($item['url'] ?? null))
                                    @php
                                        $platform = $item['platform'] ?? 'Link';
                                        $iconName = $item['icon'] ?? 'heroicon-o-globe-alt';
                                    @endphp
                                    <a
                                        href="{{ $item['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-background border border-border text-text-muted hover:text-primary hover:border-primary/40 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                                        aria-label="{{ $platform }}"
                                    >
                                        <x-icon :name="$iconName" class="w-4 h-4" />
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Newsletter Subscription Form with Progressive Enhancement --}}
                    @if(filled($footer['newsletter_heading'] ?? null) || filled($footer['newsletter_placeholder'] ?? null))
                        <div
                            class="pt-2 max-w-md"
                            x-data="{
                                submitting: false,
                                subscribed: false,
                                message: '',
                                errorMessage: '',
                                submitNewsletter(e) {
                                    this.submitting = true;
                                    this.errorMessage = '';
                                    fetch('{{ route('newsletter.store') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        body: new FormData(e.target)
                                    })
                                    .then(res => res.json().then(data => ({ ok: res.ok, data })))
                                    .then(result => {
                                        this.submitting = false;
                                        if (result.ok && result.data.success) {
                                            this.subscribed = true;
                                            this.message = result.data.message;
                                            e.target.reset();
                                        } else {
                                            let errs = result.data.errors ? Object.values(result.data.errors).flat() : [];
                                            this.errorMessage = errs[0] || result.data.message || '';
                                        }
                                    })
                                    .catch(() => {
                                        this.submitting = false;
                                        this.errorMessage = '';
                                    });
                                }
                            }"
                        >
                            @if(filled($footer['newsletter_heading'] ?? null))
                                <span class="text-xs font-semibold tracking-wider uppercase text-text/90 block mb-2">
                                    {{ $footer['newsletter_heading'] }}
                                </span>
                            @endif

                            <template x-if="subscribed">
                                <div class="p-3 rounded-theme bg-primary/10 border border-primary/20 text-primary text-xs font-medium">
                                    <span x-text="message"></span>
                                </div>
                            </template>

                            <template x-if="errorMessage">
                                <div class="p-2.5 rounded-theme bg-red-500/10 border border-red-500/20 text-red-700 text-xs mb-2">
                                    <span x-text="errorMessage"></span>
                                </div>
                            </template>

                            <form
                                x-show="!subscribed"
                                action="{{ route('newsletter.store') }}"
                                method="POST"
                                class="flex gap-2"
                                @submit.prevent="submitNewsletter($event)"
                            >
                                @csrf
                                <input type="text" name="_hp_website" value="" class="hidden sr-only" tabindex="-1" autocomplete="off" aria-hidden="true" />
                                <input type="hidden" name="_form_time" value="{{ \App\Support\SpamProtection::generateToken() }}" />

                                <input
                                    type="email"
                                    name="email"
                                    required
                                    placeholder="{{ $footer['newsletter_placeholder'] ?? '' }}"
                                    class="w-full px-3.5 py-2 text-xs rounded-theme bg-background border border-border text-text placeholder-text-muted focus:outline-none focus:ring-1 focus:ring-primary"
                                />

                                @if(filled($footer['newsletter_button'] ?? null))
                                    <button
                                        type="submit"
                                        class="px-3.5 py-2 text-xs font-semibold rounded-theme bg-primary text-white hover:bg-primary-hover focus:outline-none focus:ring-1 focus:ring-primary shrink-0 transition-colors"
                                        :disabled="submitting"
                                    >
                                        {{ $footer['newsletter_button'] }}
                                    </button>
                                @endif
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Column 2: Navigation Links --}}
                @if(count($menuLinks) > 0)
                    <div class="lg:col-span-3 flex flex-col gap-4">
                        @if(filled($footer['nav_heading'] ?? null))
                            <span class="text-xs font-semibold tracking-wider uppercase text-text/90">
                                {{ $footer['nav_heading'] }}
                            </span>
                        @endif
                        <ul class="flex flex-col gap-2.5 text-sm text-text-muted" role="list">
                            @foreach($menuLinks as $link)
                                @php
                                    $target = $link['target'] ?? 'section';
                                    $href = $target === 'section'
                                        ? '#' . ($link['section_key'] ?? '')
                                        : ($link['url'] ?? '#');
                                @endphp
                                <li>
                                    <a
                                        href="{{ $href }}"
                                        @if(!empty($link['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif
                                        class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary rounded"
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Column 3: Contact & Address Information --}}
                @php
                    $hasContact = filled($contact['email'] ?? null) ||
                                  filled($contact['phone'] ?? null) ||
                                  filled($contact['address'] ?? null) ||
                                  filled($contact['working_hours'] ?? null);
                @endphp
                @if($hasContact)
                    <div class="lg:col-span-4 flex flex-col gap-4">
                        @if(filled($footer['contact_heading'] ?? null))
                            <span class="text-xs font-semibold tracking-wider uppercase text-text/90">
                                {{ $footer['contact_heading'] }}
                            </span>
                        @endif
                        <ul class="flex flex-col gap-3 text-sm text-text-muted" role="list">
                            @if(filled($contact['address'] ?? null))
                                <li class="flex items-start gap-2.5">
                                    <x-icon name="heroicon-o-map-pin" class="w-4 h-4 mt-0.5 text-accent shrink-0" />
                                    <span>{{ $contact['address'] }}</span>
                                </li>
                            @endif

                            @if(filled($contact['phone'] ?? null))
                                <li class="flex items-center gap-2.5">
                                    <x-icon name="heroicon-o-phone" class="w-4 h-4 text-accent shrink-0" />
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}" class="hover:text-primary transition-colors">
                                        {{ $contact['phone'] }}
                                    </a>
                                </li>
                            @endif

                            @if(filled($contact['email'] ?? null))
                                <li class="flex items-center gap-2.5">
                                    <x-icon name="heroicon-o-envelope" class="w-4 h-4 text-accent shrink-0" />
                                    <a href="mailto:{{ $contact['email'] }}" class="hover:text-primary transition-colors">
                                        {{ $contact['email'] }}
                                    </a>
                                </li>
                            @endif

                            @if(filled($contact['working_hours'] ?? null))
                                <li class="flex items-center gap-2.5">
                                    <x-icon name="heroicon-o-clock" class="w-4 h-4 text-accent shrink-0" />
                                    <span>{{ $contact['working_hours'] }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Bottom Row: Copyright & Legal Links --}}
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-text-muted">
                @if(filled($copyrightResolved))
                    <p>{{ $copyrightResolved }}</p>
                @endif

                @if(count($legalLinks) > 0)
                    <ul class="flex flex-wrap items-center gap-x-6 gap-y-2" role="list">
                        @foreach($legalLinks as $legal)
                            @if(filled($legal['label'] ?? null))
                                <li>
                                    <a
                                        href="{{ $legal['url'] ?? '#' }}"
                                        class="hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary rounded"
                                    >
                                        {{ $legal['label'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </footer>
</body>
</html>
