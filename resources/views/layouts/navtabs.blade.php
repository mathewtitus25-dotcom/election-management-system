<style>
    /* Outer pill container */
    .pill-nav {
        max-width: 1100px;
        margin: 30px auto;
        background: rgba(255,255,255,0.95);
        border-radius: 999px;
        padding: 10px;
        box-shadow: 0 30px 60px rgba(79,70,229,0.18);
        display: flex;
        gap: 8px;
    }

    /* Nav item */
    .pill-nav a {
        flex: 1;
        text-align: center;
        padding: 14px 20px;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        color: rgba(55,65,81,0.85);
        transition: all 0.25s ease;
    }

    /* Hover only text */
    .pill-nav a:not(.active):hover {
        color: #4f46e5;
    }

    /* Active pill */
    .pill-nav a.active {
        background: linear-gradient(90deg,#4f46e5,#818cf8);
        color: #ffffff;
        box-shadow: 0 12px 30px rgba(79,70,229,0.45);
    }

    /* Mobile wrap */
    @media (max-width: 768px) {
        .pill-nav {
            flex-wrap: wrap;
            border-radius: 28px;
        }

        .pill-nav a {
            flex: 1 1 100%;
        }
    }
</style>

<!-- PILL NAVIGATION -->
<nav class="pill-nav">

    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
        Home
    </a>

    <a href="{{ route('register') }}"
       class="{{ request()->is('register') ? 'active' : '' }}">
        Register as Voter
    </a>
    <a href="{{ route('candidate.register') }}"
       class="{{ request()->is('candidate/apply') ? 'active' : '' }}">
        Apply as Candidate
    </a>

    <a href="{{ route('results') }}"
       class="{{ request()->is('results') ? 'active' : '' }}">
        Results
    </a>

</nav>
