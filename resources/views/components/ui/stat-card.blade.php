@props([
    'label' => '',
    'value' => '',
    'icon' => null,
    'tone' => 'primary',
    'meta' => null,
])

<article {{ $attributes->merge(['class' => "stat-card stat-card--{$tone} interactive-card"]) }} data-reveal>
    <div class="stat-card__top">
        <div>
            <p class="stat-card__label">{{ $label }}</p>
            <div class="stat-card__value">{{ $value }}</div>
        </div>

        @if($icon)
            <span class="stat-card__icon">
                <i class="bi {{ $icon }}"></i>
            </span>
        @endif
    </div>

    @if($meta || trim((string) $slot) !== '')
        <div>
            @if($meta)
                <p class="stat-card__meta">{{ $meta }}</p>
            @endif

            @if(trim((string) $slot) !== '')
                <div class="stat-card__extra">{{ $slot }}</div>
            @endif
        </div>
    @endif
</article>
