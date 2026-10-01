@props([
    'settings' => null,
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogImage' => null,
])

<x-layouts.landing
    :settings="$settings"
    :title="$title"
    :description="$description"
    :canonical="$canonical"
    :og-image="$ogImage"
    {{ $attributes }}
>
    {{ $slot }}
</x-layouts.landing>
