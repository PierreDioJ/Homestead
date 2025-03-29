<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Homestead | Личный кабинет</title>
    <link rel="stylesheet" href="/css/styles-profile.css">
    <link rel="stylesheet" href="/css/styles-general.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <x-header />
    <div class="wrapper">
        <main class="main-page grid--2 container">
            <div class="left-side-profile">
                <h2 class="profile-welcome">Добро пожаловать, {{ $user->name }}</h2>

                @if ($user->isLandlord())
                    <div class="profile-listings">
                        <h2 class="profile-listings-title">Ваши объявления</h2>
                        @if ($listings->isEmpty())
                            <p class="profile-listings-text">У вас пока нет объявлений.</p>
                        @else
                            <div class="listings-grid">
                                @foreach ($listings as $listing)
                                    <div class="listing-card">
                                        <a href="{{ route('listings.show', $listing->id) }}" target="_blank">
                                            <img src="{{ Storage::url($listing->photo) }}" alt="Фото объявления"
                                                class="listing-card-img">
                                            <div class="listing-card-info">
                                                <h4>{{ $listing->title }}</h4>
                                                <p>{{ $listing->location }}</p>
                                                <p>{{ $listing->price }} ₽/ночь</p>
                                            </div>
                                        </a>

                                        <div class="listing-actions">
                                            <a href="{{ route('listings.edit', $listing->id) }}" class="btn-edit">Редактировать</a>

                                            <form action="{{ route('listings.delete', $listing->id) }}" method="POST"
                                                onsubmit="return confirm('Вы уверены, что хотите удалить это объявление?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>


            <div class="right-side-profile">
                @if (session('success'))
                    <p class="success-message">{{ session('success') }}</p>
                @endif
                <form action="/profile" method="POST" class="profile-form">
                    <h2 class="form-title">Это ваш профиль</h2>
                    @csrf
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>

                    <label for="password">Новый пароль (если хотите изменить):</label>
                    <input type="password" id="password" name="password">

                    <label for="password_confirmation">Подтвердите новый пароль:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">

                    <button type="submit" class="btn-submit">Обновить</button>
                </form>
                <button type="button" class="btn-bookings">Мои бронирования</button>
                <button type="submit" class="btn-favorite">Избранные объявления</button>
                @if ($errors->any())
                    <div class="error">
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li class="error-text">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </main>

        <div id="bookingsModal" class="modal">
            <div class="modal-content">
                <span class="btn-close" id="closeBookings">&times;</span>
                <h2>Мои бронирования</h2>
                <div class="bookings-grid">
                    @if ($bookings->isEmpty())
                        <p class="no-bookings">У вас пока нет бронирований.</p>
                    @else
                                    @foreach ($bookings as $booking)
                                                    @php
                                                        $checkIn = \Carbon\Carbon::parse($booking->check_in_date);
                                                        $checkOut = \Carbon\Carbon::parse($booking->check_out_date);
                                                        $nights = $checkIn->diffInDays($checkOut);
                                                        $totalPrice = $nights * $booking->listing->price;
                                                    @endphp

                                                    <div class="booking-item">
                                                        <img class="booking-img" src="{{ asset('storage/' . $booking->listing->photo) }}"
                                                            alt="Бронирование">
                                                        <div class="booking-details">
                                                            <h3>{{ $booking->listing->title }}</h3>
                                                            <p>Дата: {{ $checkIn->format('d.m.Y') }} - {{ $checkOut->format('d.m.Y') }}</p>
                                                            <p>Цена: {{ number_format($booking->listing->price, 0, '', ' ') }} ₽/ночь</p>
                                                            <p>Итого: {{ number_format($totalPrice, 0, '', ' ') }} ₽</p>
                                                        </div>
                                                    </div>
                                    @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div id="favoriteModal" class="modal">
            <div class="modal-content">
                <span class="btn-close">&times;</span>
                <h2 class="title-favorite ">Избранное</h2>
                <div class="favorites-grid">

                </div>
            </div>
        </div>
        <x-footer />
    </div>
    <script src="/js/favoritesModal.js"></script>
    <script src="/js/bookingsModal.js"></script>
    <script src="/js/listingLimit.js"></script>
</body>

</html>