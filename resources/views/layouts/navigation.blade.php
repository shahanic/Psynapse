<nav x-data="{ open: false }">
    <div class="nav-inner">

        <!-- Left: Logo -->
        <div class="nav-brand">
            <a href="{{ route('dashboard') }}" class="nav-logo-link">
                <div class="nav-logo"><span>Ps</span></div>
                <span class="brand-name">Psynapse</span>
            </a>
        </div>

        <!-- Right: Desktop -->
        <div class="nav-right">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                Dashboard
            </a>

            <!-- Dropdown -->
            <div class="nav-dropdown-menu">
    <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">Profile</a>

    @if(auth()->user()->role === 'admin')
    <div class="nav-dropdown-divider"></div>
    <a href="/admin/dashboard" class="nav-dropdown-item">Admin Panel</a>
    @endif

    @if(auth()->user()->role === 'superadmin')
    <div class="nav-dropdown-divider"></div>
    <a href="/superadmin/dashboard" class="nav-dropdown-item">SuperAdmin Panel</a>
    <a href="/superadmin/upload" class="nav-dropdown-item">Upload Exam</a>
    @endif

    <div class="nav-dropdown-divider"></div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-dropdown-item nav-dropdown-item-full">
            Log Out
        </button>
    </form>
</div>
        <!-- Hamburger: Mobile -->
        <button @click="open = !open" class="nav-hamburger">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'block': !open}" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path :class="{'hidden': !open, 'block': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden nav-mobile">
        <div class="nav-mobile-links">
            <a href="{{ route('dashboard') }}" class="nav-mobile-link">Dashboard</a>
        </div>
        <div class="nav-mobile-user">
            <p class="nav-mobile-name">{{ Auth::user()->name }}</p>
            <p class="nav-mobile-email">{{ Auth::user()->email }}</p>
            <a href="{{ route('profile.edit') }}" class="nav-mobile-link">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-mobile-link nav-mobile-link-full">Log Out</button>
            </form>
        </div>
    </div>
</nav>