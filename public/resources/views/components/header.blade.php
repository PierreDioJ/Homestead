<header class="header">
    <section class="header-page">
        <div class="container">
            <div class="header-nav-page">
                <a href="/" class="logo-link">
                    <img src="../img/Logo-header.png" class="logo-header" alt="Homestead Logo">
                </a>
                <nav class="header-nav">
                    <ul class="nav-list__header">
                        @auth
                            <li class="nav-item__header">
                                <a href="{{ route('listings.index') }}" class="nav-link__header">Просмотр жилья</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="{{ route('chat.list') }}" class="nav-link__header">Мои чаты</a>
                            </li>
                            @if (Auth::user()->isLandlord())
                                <li class="nav-item__header">
                                    <a href="{{ route('listings.create') }}" class="nav-link__header">Сдать жильё</a>
                                </li>
                            @endif
                            @if (Auth::user()->isAdmin())
                                <li class="nav-item__header">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link__header">Панель администратора</a>
                                </li>
                            @endif
                            <li class="nav-item__header">
                                <a href="/profile" class="nav-link__header">Привет, {{ Auth::user()->name }}</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="{{ route('logout') }}" class="nav-link__header"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Выйти
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endauth
                        @guest
                            <li class="nav-item__header">
                                <a href="{{ route('listings.index') }}" class="nav-link__header">Просмотр жилья</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="/login" class="nav-link__header">Войти</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="/register" class="nav-link__header">Регистрация</a>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</header>