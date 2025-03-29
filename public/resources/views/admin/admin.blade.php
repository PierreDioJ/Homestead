<!DOCTYPE html>
<html lang="ru">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <title>Homestead | Админ-панель</title>
    <link rel="stylesheet" href="/css/styles-admin.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
    <x-header />
    <div class="wrapper">
        <main class="main">
            <div class="main-container">
                <!-- Сайдбар -->
                <aside class="sidebar">
                    <nav>
                        <ul>
                            <p class="menu-link-title">Панель управления</p>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.users.index') }}">👥
                                    Пользователи</a></li>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.listings.index') }}">📌
                                    Объявления</a></li>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.bookings.index') }}">📅
                                    Бронирования</a></li>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.payments.index') }}">💳
                                    Платежи</a></li>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.stats.index') }}">📊
                                    Статистика и аналитика</a></li>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.reports.index') }}">📊
                                    Репорты</a></li>
                            <li><a href="#" class="menu-link" data-url="{{ route('admin.reviews.index') }}">📊
                                    Просмотр отзывов объявлений</a></li>
                        </ul>
                    </nav>
                </aside>

                <!-- Контентная область -->
                <div class="main-content" id="ajax-content">
                    <h1>Добро пожаловать в админ-панель</h1>
                    <p>Здесь вы можете управлять пользователями, объявлениями, бронированиями и платежами.</p>

                    <div class="stats">
                        <div class="stat-card">
                            <h3>👥 Пользователи</h3>
                            <p>{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="stat-card">
                            <h3>📌 Объявления</h3>
                            <p>{{ $totalListings ?? 0 }}</p>
                        </div>
                        <div class="stat-card">
                            <h3>📅 Бронирования</h3>
                            <p>{{ $totalBookings ?? 0 }}</p>
                        </div>
                        <div class="stat-card">
                            <h3>💳 Общая сумма платежей</h3>
                            <p>{{ number_format($totalPayments ?? 0, 2, ',', ' ') }} ₽</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-footer />
    </div>

    <!-- Подключение jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.menu-link').click(function (e) {
                e.preventDefault();
                let url = $(this).data('url');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (data) {
                        $('#ajax-content').html(data);
                    },
                    error: function () {
                        alert('Ошибка загрузки данных!');
                    }
                });
            });
        });
    </script>
</body>

</html>