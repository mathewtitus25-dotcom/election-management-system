@php
    $appName = config('app.name', 'VoteDesk');
    $user = Auth::user();
    $pageTitle = trim($__env->yieldContent('page_title')) ?: $appName;
    $pageSubtitle = trim($__env->yieldContent('page_subtitle')) ?: 'Secure election operations with calmer flows, clearer decisions, and reliable public reporting.';

    $dashboardItem = null;

    if ($user) {
        $dashboardItem = match ($user->role) {
            'admin' => ['label' => 'Admin Dashboard', 'href' => route('admin.dashboard'), 'icon' => 'bi-speedometer2', 'hint' => 'Control center', 'active' => request()->routeIs('admin.dashboard')],
            'blo' => ['label' => 'Officer Dashboard', 'href' => route('blo.dashboard'), 'icon' => 'bi-shield-check', 'hint' => 'Approvals and results', 'active' => request()->routeIs('blo.dashboard')],
            'candidate' => ['label' => 'Candidate Dashboard', 'href' => route('candidate.dashboard'), 'icon' => 'bi-megaphone', 'hint' => 'Campaign snapshot', 'active' => request()->routeIs('candidate.dashboard')],
            'voter' => ['label' => 'Voting Booth', 'href' => route('voter.dashboard'), 'icon' => 'bi-check2-square', 'hint' => 'Ballot and verification', 'active' => request()->routeIs('voter.dashboard')],
            default => null,
        };
    }

    $primaryNavigation = [
        ['label' => 'Home', 'href' => route('welcome'), 'icon' => 'bi-house-door', 'hint' => 'Overview', 'active' => request()->routeIs('welcome')],
    ];

    if ($dashboardItem) {
        $primaryNavigation[] = $dashboardItem;
    }

    $primaryNavigation[] = [
        'label' => 'Election Results',
        'href' => $user ? route('results') : route('login'),
        'icon' => 'bi-bar-chart-line',
        'hint' => $user ? 'Live and certified counts' : 'Sign in to view',
        'active' => request()->routeIs('results'),
    ];

    $secondaryNavigation = [];

    if (! $user) {
        $secondaryNavigation[] = ['label' => 'Login', 'href' => route('login'), 'icon' => 'bi-box-arrow-in-right', 'hint' => 'Role-based access', 'active' => request()->routeIs('login')];
        $secondaryNavigation[] = ['label' => 'Register', 'href' => route('register'), 'icon' => 'bi-person-plus', 'hint' => 'Voter onboarding', 'active' => request()->routeIs('register')];
        $secondaryNavigation[] = ['label' => 'Candidate Apply', 'href' => route('candidate.register'), 'icon' => 'bi-person-badge', 'hint' => 'Nomination form', 'active' => request()->routeIs('candidate.register')];
    } elseif ($user->role === 'admin') {
        $secondaryNavigation[] = ['label' => 'Verification Logs', 'href' => route('admin.voters.index'), 'icon' => 'bi-list-columns-reverse', 'hint' => 'Audit captured activity', 'active' => request()->routeIs('admin.voters.index')];
    }

    $hasViteAssets = file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#10233d">
    <title>{{ $pageTitle }} | {{ $appName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @if($hasViteAssets)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>{!! file_get_contents(resource_path('css/app.css')) !!}</style>
        <script defer>{!! file_get_contents(resource_path('js/app.js')) !!}</script>
    @endif
    @stack('styles')
</head>
<body class="@yield('body_class')">
    <a href="#app-content" class="skip-link">Skip to content</a>

    <div class="app-shell">
        <div class="app-overlay" data-sidebar-overlay></div>

        <aside id="app-sidebar" class="app-sidebar" aria-label="Primary navigation">
            <div class="app-sidebar__header">
                <div class="app-sidebar__brand">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ $appName }} logo" width="34" height="34">
                </div>
                <div>
                    <h1 class="app-sidebar__title">{{ $appName }}</h1>
                    <span class="app-sidebar__subtitle">Digital election operations</span>
                </div>
            </div>

            @auth
                <section class="app-sidebar__profile">
                    <span class="app-sidebar__eyebrow">Signed In</span>
                    <div class="app-sidebar__profile-name">{{ $user->name }}</div>
                    <div class="app-sidebar__profile-meta">{{ ucfirst($user->role) }} workspace</div>
                </section>
            @endauth

            <nav class="app-sidebar__nav">
                <div class="app-sidebar__group">
                    <div class="app-sidebar__group-title">Primary</div>
                    @foreach($primaryNavigation as $item)
                        <a href="{{ $item['href'] }}" class="app-sidebar__link {{ $item['active'] ? 'is-active' : '' }}" @if($item['active']) aria-current="page" @endif>
                            <i class="bi {{ $item['icon'] }}"></i>
                            <span>
                                <span class="d-block fw-semibold">{{ $item['label'] }}</span>
                                <small>{{ $item['hint'] }}</small>
                            </span>
                        </a>
                    @endforeach
                </div>

                @if(count($secondaryNavigation))
                    <div class="app-sidebar__group">
                        <div class="app-sidebar__group-title">Quick Access</div>
                        @foreach($secondaryNavigation as $item)
                            <a href="{{ $item['href'] }}" class="app-sidebar__link {{ $item['active'] ? 'is-active' : '' }}" @if($item['active']) aria-current="page" @endif>
                                <i class="bi {{ $item['icon'] }}"></i>
                                <span>
                                    <span class="d-block fw-semibold">{{ $item['label'] }}</span>
                                    <small>{{ $item['hint'] }}</small>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </nav>

            <div class="app-sidebar__footer">
                <span class="app-sidebar__subtitle">Built for clarity, verification, and trust.</span>
            </div>
        </aside>

        <div class="app-main">
            <header class="app-topbar">
                <button type="button" class="app-topbar__toggle" data-sidebar-toggle aria-controls="app-sidebar" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <div class="app-topbar__meta">
                    <h2 class="app-topbar__title">{{ $pageTitle }}</h2>
                </div>

                <div class="app-topbar__actions">
                    @auth
                        <span class="status-pill status-pill--primary">
                            <i class="bi bi-person-badge"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="prevent-double" data-pending-text="Signing out...">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    @else
                        @if(! request()->routeIs('login'))
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Login
                            </a>
                        @endif
                        @if(! request()->routeIs('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i>
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </header>

            <main id="app-content" class="app-content">
                @yield('content')
            </main>

            <footer class="app-footer">
                <small>&copy; {{ date('Y') }} {{ $appName }}. Designed for transparent, role-aware election management.</small>
            </footer>
        </div>
    </div>

    <div class="app-toast-stack" aria-live="polite" aria-atomic="true">
        @if(session('success'))
            <div class="app-toast app-toast--success" data-toast data-timeout="4200">
                <span class="app-toast__icon"><i class="bi bi-check2-circle"></i></span>
                <div>{{ session('success') }}</div>
                <button type="button" class="app-toast__close" data-toast-close aria-label="Dismiss message">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="app-toast app-toast--danger" data-toast data-timeout="5200">
                <span class="app-toast__icon"><i class="bi bi-exclamation-octagon"></i></span>
                <div>{{ session('error') }}</div>
                <button type="button" class="app-toast__close" data-toast-close aria-label="Dismiss message">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(isset($errors) && $errors instanceof \Illuminate\Support\MessageBag && $errors->any())
            <div class="app-toast app-toast--danger" data-toast data-timeout="6400">
                <span class="app-toast__icon"><i class="bi bi-exclamation-triangle"></i></span>
                <div>
                    <strong class="d-block mb-1">Please review the highlighted fields.</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="app-toast__close" data-toast-close aria-label="Dismiss validation message">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
