@props([
    'title' => null,
    'description' => null,
    'primaryColor' => '#059669',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Aim Charity') }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        :root {
            --brand-primary: {{ $primaryColor }};
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col font-sans text-slate-800 selection:bg-emerald-500 selection:text-white">
    {{ $slot }}
</body>
</html>
