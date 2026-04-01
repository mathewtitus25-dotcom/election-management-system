@php
    $resultsLink = Auth::check() ? route('results') : route('login');
@endphp

<nav class="marketing-tabs" data-reveal>
    <a href="{{ route('welcome') }}" class="marketing-tabs__link {{ request()->routeIs('welcome') ? 'is-active' : '' }}">
        <i class="bi bi-house-door"></i>
        Home
    </a>

    <a href="{{ route('register') }}" class="marketing-tabs__link {{ request()->routeIs('register') ? 'is-active' : '' }}">
        <i class="bi bi-person-plus"></i>
        Register as Voter
    </a>

    <a href="{{ route('candidate.register') }}" class="marketing-tabs__link {{ request()->routeIs('candidate.register') ? 'is-active' : '' }}">
        <i class="bi bi-person-badge"></i>
        Apply as Candidate
    </a>

    <a href="{{ $resultsLink }}" class="marketing-tabs__link {{ request()->routeIs('results') ? 'is-active' : '' }}">
        <i class="bi bi-bar-chart-line"></i>
        {{ Auth::check() ? 'Results' : 'Login for Results' }}
    </a>
</nav>
