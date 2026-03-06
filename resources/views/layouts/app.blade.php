<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoteDesk</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #e0f2fe 0%, #eef2ff 50%, #f5f3ff 100%);
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .navbar {
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .nav-link {
            font-weight: 500;
            color: #4b5563 !important;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4a90e2;
        }
        .btn-custom{
            background-color:#4a90e2;
            border-color:#4a90e2;
            color:#fff;
        }

        /* When mouse cursor is ON button */
        .btn-custom:hover{
            background-color:#3f7fc4;   /* slightly darker */
            border-color:#3f7fc4;
            color:#fff;
        }

        /* Optional: when clicking */
        .btn-custom:active{
            background-color:#366fae;
            border-color:#366fae;
        }

        .hero-section {
            padding: 4rem 0;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .step-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            margin: 0 auto 1rem;
        }

        /* Capitalize first letter of each word */
        .capitalize-input {
            text-transform: capitalize;
        }
    </style>
</head>
<body class="bg-gradient-primary min-vh-100 d-flex flex-column">

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="VoteDesk Logo" style="width: 40px; height: 40px; object-fit: contain; border-radius: 8px;">
                <div class="d-flex flex-column">
                    <span class="fw-bold lh-1 text-dark">VoteDesk</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }} 
                                <span class="badge bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                            </span>
                        </li>
                        <li class="nav-item ms-2">
                             @if(Auth::user()->role == 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">Dashboard</a>
                             @elseif(Auth::user()->role == 'blo')
                                <a href="{{ route('blo.dashboard') }}" class="btn btn-sm btn-outline-primary">Dashboard</a>
                             @elseif(Auth::user()->role == 'candidate')
                                <a href="{{ route('candidate.dashboard') }}" class="btn btn-sm btn-outline-primary">Dashboard</a>
                             @elseif(Auth::user()->role == 'voter')
                                <a href="{{ route('voter.dashboard') }}" class="btn btn-sm btn-outline-primary">Dashboard</a>
                             @endif
                        </li>
                        <li class="nav-item ms-2">
                            <a href="{{ route('results') }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-bar-chart-fill me-1"></i> Results
                            </a>
                        </li>

                        <li class="nav-item ms-2">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-center text-muted">
            <p class="mb-0">&copy; {{ date('Y') }} VoteDesk. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Capitalize first letter of each word in inputs with .capitalize-input class
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('capitalize-input')) {
                let cursorPosition = e.target.selectionStart;
                let value = e.target.value;
                
                // Capitalize first letter of each word
                let words = value.split(' ');
                for (let i = 0; i < words.length; i++) {
                    if (words[i].length > 0) {
                        words[i] = words[i][0].toUpperCase() + words[i].substr(1);
                    }
                }
                
                let newValue = words.join(' ');
                if (value !== newValue) {
                    e.target.value = newValue;
                    // Restore cursor position
                    e.target.setSelectionRange(cursorPosition, cursorPosition);
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
