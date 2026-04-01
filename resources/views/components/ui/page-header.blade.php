@props([
    'eyebrow' => null,
    'title' => '',
    'subtitle' => null,
])

<section {{ $attributes->merge(['class' => 'page-hero']) }} data-reveal>
    <div>
        @if($eyebrow)
            <div class="page-hero__eyebrow">{{ $eyebrow }}</div>
        @endif

        <h1 class="page-hero__title">{{ $title }}</h1>

        @if($subtitle)
            <p class="page-hero__subtitle mt-3">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="page-hero__actions">
            {{ $actions }}
        </div>
    @endisset

    @if(trim((string) $slot) !== '')
        <div class="page-hero__meta">
            {{ $slot }}
        </div>
    @endif
</section>
