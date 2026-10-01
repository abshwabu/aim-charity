@props([
    'content' => null,
    'class' => '',
])

@php
    use App\Support\HtmlSanitizer;

    $rawContent = $content ?? (string) $slot;
    $sanitized = HtmlSanitizer::clean($rawContent);
@endphp

@if(filled($sanitized))
    <div {{ $attributes->merge(['class' => "prose max-w-none prose-headings:font-heading prose-headings:text-current prose-p:leading-relaxed prose-a:text-primary hover:prose-a:underline prose-img:rounded-theme prose-strong:text-current opacity-90 {$class}"]) }}>
        {!! $sanitized !!}
    </div>
@endif
