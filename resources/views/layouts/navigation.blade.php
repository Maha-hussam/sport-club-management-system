<nav x-data="{ open: false }" class="club-navbar">
    <div class="club-container">
        <div class="navbar-content">

            <a href="{{ auth()->user()->role === 'trainer'
    ? route('trainer.dashboard')
    : route('player.dashboard') }}" class="club-logo">
                SPORT<span>CLUB.</span>
            </a>

            <div class="desktop-actions">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">
                        Log Out <span aria-hidden="true">&rarr;</span>
                    </button>
                </form>
            </div>

            <button @click="open = !open" class="mobile-menu-button" type="button" aria-label="Toggle navigation"
                :aria-expanded="open.toString()">
                <span x-show="!open">☰</span>
                <span x-show="open" x-cloak>✕</span>
            </button>

        </div>

        <div x-show="open" x-transition x-cloak class="mobile-menu">
            <div class="mobile-user">
                <span class="user-name">
                    {{ Auth::user()->first_name }}
                    {{ Auth::user()->last_name }}
                </span>

                <span class="user-email">
                    {{ Auth::user()->email }}
                </span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button mobile-logout">
                    Log Out <span aria-hidden="true">&rarr;</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    .club-navbar {
        background: #1a211a;
        border-bottom: 1px solid #303a2e;
    }

    .club-container {
        width: 90%;
        max-width: 1120px;
        margin: auto;
    }

    .navbar-content {
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .club-logo {
        color: #f5f7f2;
        font-size: 24px;
        font-weight: bold;
        white-space: nowrap;
    }

    .club-logo span {
        color: #b7f34a;
    }

    .desktop-actions {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .user-name {
        color: #f5f7f2;
        font-size: 14px;
        font-weight: 600;
    }

    .logout-button {
        display: inline-block;
        padding: 10px 18px;
        background: #b7f34a;
        color: #101510;
        border: 1px solid #b7f34a;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: opacity .2s;
    }

    .logout-button:hover {
        opacity: .85;
    }

    .mobile-menu-button {
        display: none;
        padding: 8px 12px;
        background: transparent;
        border: 1px solid #414a3f;
        border-radius: 8px;
        color: #b7f34a;
        font-size: 22px;
        cursor: pointer;
    }

    .mobile-menu {
        padding: 18px 0 22px;
        border-top: 1px solid #303a2e;
    }

    .mobile-user {
        display: flex;
        flex-direction: column;
        gap: 3px;
        margin-bottom: 16px;
    }

    .user-email {
        color: #a5afa2;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    [x-cloak] {
        display: none !important;
    }

    @media (max-width: 640px) {
        .navbar-content {
            min-height: 72px;
        }

        .club-logo {
            font-size: 22px;
        }

        .desktop-actions {
            display: none;
        }

        .mobile-menu-button {
            display: block;
        }

        .mobile-logout {
            width: 100%;
        }
    }
</style>