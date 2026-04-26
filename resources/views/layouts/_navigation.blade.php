<nav class="navbar navbar-dark bg-dark navbar-expand-lg z-1030">
    <div class="container">
        @auth
            <a class="navbar-brand" href="{{ route('user_friendships.index') }}">
                {{ Auth::user()->name }}
                <small>{{ Auth::user()->username }}</small>
            </a>
        @else
            <a class="navbar-brand" href="{{ route('home') }}">
                پلتفرم بازی اکرز
            </a>
        @endauth
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1"
            aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent1">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('game_xos.index') }}">{{ __('GameXo') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('game_hokms.index') }}">{{ __('GameHokm') }}</a>
                    </li>
                @endauth
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @else
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
