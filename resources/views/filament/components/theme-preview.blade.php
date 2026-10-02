@php
    $theme = $theme ?? $this->data['theme'] ?? [];
    $primary = $theme['primary'] ?? '#059669';
    $secondary = $theme['secondary'] ?? '#0d9488';
    $accent = $theme['accent'] ?? '#f59e0b';
    $background = $theme['background'] ?? '#f8fafc';
    $surface = $theme['surface'] ?? '#ffffff';
    $text = $theme['text'] ?? '#0f172a';
    $headingFont = ($theme['heading_font'] ?? '') === 'custom'
        ? ($theme['heading_font_custom'] ?? 'Instrument Sans')
        : ($theme['heading_font'] ?? 'Instrument Sans');
    $bodyFont = ($theme['body_font'] ?? '') === 'custom'
        ? ($theme['body_font_custom'] ?? 'Instrument Sans')
        : ($theme['body_font'] ?? 'Instrument Sans');
    $radius = match ($theme['corner_radius'] ?? 'soft') {
        'sharp' => '0px',
        'pill' => '9999px',
        default => '12px',
    };
@endphp

<div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
    <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-3 dark:border-gray-800">
        <div>
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Theme Live Preview</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400">Live preview reflects your selected colors, fonts, and corner radius in real time.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">
            Live Swatch
        </span>
    </div>

    <!-- Palette Swatches -->
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        <div class="flex items-center gap-2.5 rounded-lg border border-gray-100 p-2.5 dark:border-gray-800">
            <div class="h-8 w-8 shrink-0 rounded-md border border-black/10 shadow-inner" style="background-color: {{ $primary }}"></div>
            <div class="min-w-0">
                <div class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">Primary</div>
                <div class="font-mono text-[11px] text-gray-500">{{ $primary }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 rounded-lg border border-gray-100 p-2.5 dark:border-gray-800">
            <div class="h-8 w-8 shrink-0 rounded-md border border-black/10 shadow-inner" style="background-color: {{ $secondary }}"></div>
            <div class="min-w-0">
                <div class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">Secondary</div>
                <div class="font-mono text-[11px] text-gray-500">{{ $secondary }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 rounded-lg border border-gray-100 p-2.5 dark:border-gray-800">
            <div class="h-8 w-8 shrink-0 rounded-md border border-black/10 shadow-inner" style="background-color: {{ $accent }}"></div>
            <div class="min-w-0">
                <div class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">Accent</div>
                <div class="font-mono text-[11px] text-gray-500">{{ $accent }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 rounded-lg border border-gray-100 p-2.5 dark:border-gray-800">
            <div class="h-8 w-8 shrink-0 rounded-md border border-black/10 shadow-inner" style="background-color: {{ $background }}"></div>
            <div class="min-w-0">
                <div class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">Background</div>
                <div class="font-mono text-[11px] text-gray-500">{{ $background }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 rounded-lg border border-gray-100 p-2.5 dark:border-gray-800">
            <div class="h-8 w-8 shrink-0 rounded-md border border-black/10 shadow-inner" style="background-color: {{ $surface }}"></div>
            <div class="min-w-0">
                <div class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">Surface</div>
                <div class="font-mono text-[11px] text-gray-500">{{ $surface }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2.5 rounded-lg border border-gray-100 p-2.5 dark:border-gray-800">
            <div class="h-8 w-8 shrink-0 rounded-md border border-black/10 shadow-inner" style="background-color: {{ $text }}"></div>
            <div class="min-w-0">
                <div class="truncate text-xs font-medium text-gray-700 dark:text-gray-300">Text</div>
                <div class="font-mono text-[11px] text-gray-500">{{ $text }}</div>
            </div>
        </div>
    </div>

    <!-- Mini Landing Card Mockup -->
    <div class="overflow-hidden rounded-xl border border-gray-200 p-6 transition-all" style="background-color: {{ $background }};">
        <div class="mx-auto max-w-xl border border-black/5 p-6 shadow-sm" style="background-color: {{ $surface }}; color: {{ $text }}; border-radius: {{ $radius }};">
            <div class="mb-3 flex items-center justify-between">
                <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wide shadow-sm" style="background-color: {{ $accent }}; color: #ffffff; border-radius: {{ $radius }};">
                    Impact Spotlight
                </span>
                <span class="text-xs opacity-70" style="font-family: '{{ $bodyFont }}', sans-serif;">Corner Radius: {{ ucfirst($theme['corner_radius'] ?? 'soft') }}</span>
            </div>

            <h3 class="mb-2 text-xl font-bold tracking-tight" style="font-family: '{{ $headingFont }}', sans-serif; color: {{ $text }};">
                Aim Charity Town Association
            </h3>

            <p class="mb-5 text-sm leading-relaxed opacity-80" style="font-family: '{{ $bodyFont }}', sans-serif;">
                Aim Charity was started by a group of friends in our small town to support elderly neighbors, families in need, and school children through weekly pooled donations.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" class="px-4 py-2 text-xs font-semibold shadow-sm transition-opacity hover:opacity-90" style="background-color: {{ $primary }}; color: #ffffff; border-radius: {{ $radius }};">
                    Support Our Programs
                </button>
                <button type="button" class="px-4 py-2 text-xs font-semibold transition-opacity hover:opacity-90" style="background-color: transparent; border: 1.5px solid {{ $secondary }}; color: {{ $secondary }}; border-radius: {{ $radius }};">
                    Learn More
                </button>
            </div>
        </div>
    </div>
</div>
