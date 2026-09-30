<x-layout
    :title="$settings->branding['site_name'] ?? config('app.name', 'Aim Charity')"
    :description="$settings->seo['meta_description'] ?? null"
    :primary-color="$settings->theme['primary'] ?? '#059669'"
>
    @include('landing.sections.hero', [
        'settings' => $settings,
        'hero' => $hero,
    ])
</x-layout>
