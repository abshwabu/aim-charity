<x-layouts.landing
    :settings="$settings"
    :title="$settings->branding['site_name'] ?? config('app.name', 'Aim Charity')"
    :description="$settings->seo['meta_description'] ?? null"
>
    @foreach($sections as $section)
        @if(view()->exists("landing.sections.{$section->type}"))
            @include("landing.sections.{$section->type}", [
                'section' => $section,
                'settings' => $settings,
                'items' => $items[$section->type] ?? collect(),
                'allItems' => $items,
            ])
        @endif
    @endforeach
</x-layouts.landing>
