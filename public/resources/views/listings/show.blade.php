<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Homestead | Детали объявления</title>
    <link rel="stylesheet" href="/css/styles-show.css">
    <link rel="stylesheet" href="/css/styles-general.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
    <x-header />
    <div class="wrapper">
        <div class="container">
            <main class="main">
                <div class="main-grid">
                    <div>
                        <div class="header-listing">
                            <h1 class="listing-title">{{ $listing->title }}</h1>
                            <p class="listing-location">{{ $listing->location }}</p>
                        </div>
                        <div class="gallery">
                            <img src="{{ asset('storage/' . $listing->photo) }}" alt="Фото-1" class="listing-img_1">
                            <img src="{{ asset('storage/' . $listing->photo) }}" alt="Фото-2" class="listing-img_2">
                            <img src="{{ asset('storage/' . $listing->photo) }}" alt="Фото-3" class="listing-img_3">
                            <img src="{{ asset('storage/' . $listing->photo) }}" alt="Фото-4" class="listing-img_4">
                        </div>
                        <button type="submit" class="show-img">Показать все фото</button>
                        <div class="info">
                            <h2 class="title-info">О жилье</h2>
                            <p class="desc-info">{{$listing->description}}</p>
                            <ul class="desc-list">
                                <li class="desc-item">{{$listing->guests}} гостей</li>
                                <li class="desc-item">{{$listing->location}}</li>
                            </ul>
                            <h2>Отзывы</h2>

                            <!-- Форма для отправки отзыва -->
                            @auth
                                <form action="{{ route('reviews.store', $listing->id) }}" method="POST">
                                    @csrf
                                    <label for="rating">Оценка:</label>
                                    <select name="rating" id="rating">
                                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                        <option value="4">⭐️⭐️⭐️⭐️</option>
                                        <option value="3">⭐️⭐️⭐️</option>
                                        <option value="2">⭐️⭐️</option>
                                        <option value="1">⭐️</option>
                                    </select>

                                    <label for="comment">Отзыв:</label>
                                    <textarea name="comment" id="comment" rows="4" required></textarea>

                                    <button type="submit">Оставить отзыв</button>
                                </form>
                            @else
                                <p><a href="{{ route('login') }}">Войдите</a>, чтобы оставить отзыв.</p>
                            @endauth

                            <!-- Вывод существующих отзывов -->
                            @if ($listing->reviews->count() > 0)
                                @foreach ($listing->reviews as $review)
                                    <div class="review">
                                        <p><strong>{{ $review->user->name }}</strong> ({{ $review->rating }}⭐️)</p>
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p>Пока отзывов нет.</p>
                            @endif
                        </div>
                    </div>
                    <div class="details">
                        <h2>{{ $listing->price }} ₽ / ночь</h2>
                        <p>Ближайшая свободная дата:
                            <strong>{{ $nextAvailableDate ? $nextAvailableDate : 'Нет доступных дат' }}</strong>
                        </p>
                        <button type="submit" class="report-btn">&#9873; Пожаловаться на объявление</button>

                        <div id="reportModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <h2>Пожаловаться на объявление</h2>
                                <form id="reportForm" data-listing-id="{{ $listing->id }}">
                                    <div class="report-reasons">
                                        <label>
                                            <input type="radio" name="reason" value="fraud" required>
                                            Это мошенничество
                                        </label>
                                        <label>
                                            <input type="radio" name="reason" value="fake">
                                            Это не настоящее жилье
                                        </label>
                                        <label>
                                            <input type="radio" name="reason" value="incorrect-info">
                                            В объявлении указана неверная информация
                                        </label>
                                        <label>
                                            <input type="radio" name="reason" value="other">
                                            Другое
                                        </label>
                                    </div>
                                    <textarea name="details" placeholder="Дополнительные детали (необязательно)"
                                        rows="4"></textarea>
                                    <button type="submit">Отправить жалобу</button>
                                </form>
                            </div>
                        </div>

                        <form action="{{ route('payment.create') }}" method="POST">
                            @csrf

                            <div class="date-row">
                                <div class="date-select">
                                    <label for="check-in">Прибытие:</label>
                                    <input type="text" id="check-in" name="check_in_date" placeholder="Выберите дату"
                                        required>
                                </div>
                                <div class="date-select">
                                    <label for="check-out">Выезд:</label>
                                    <input type="text" id="check-out" name="check_out_date" placeholder="Выберите дату"
                                        required>
                                </div>
                            </div>

                            <div class="guest-select">
                                <label for="guests">Количество гостей:</label>
                                <select id="guests" name="guests" required>
                                    @for ($i = 1; $i <= $listing->guests; $i++)
                                                                        <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>
                                                                            @php
                                                                                // Логика для склонения
                                                                                if ($i == 1) {
                                                                                    $guestText = 'гость';
                                                                                } elseif ($i >= 2 && $i <= 4) {
                                                                                    $guestText = 'гостя';
                                                                                } else {
                                                                                    $guestText = 'гостей';
                                                                                }
                                                                            @endphp
                                                                            {{ $i }} {{ $guestText }}
                                                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <input type="hidden" name="amount" value="{{ $listing->price }}">
                            <input type="hidden" name="listing_id" value="{{ $listing->id }}">

                            @if(auth()->check())
                                <button type="submit" class="btn btn-primary">Оплатить</button>
                            @else
                                <button type="button" class="btn btn-primary" onclick="redirectToLogin()">Оплатить</button>
                            @endif
                        </form>


                        <p style="text-align: center; color: gray; font-size: 12px; margin-top: 10px;">Вы ничего не
                            платите
                            сейчас</p>
                    </div>
                </div>
            </main>
        </div>

        <div id="booking-modal" class="modal">
            <div class="modal-content">
                <h2>Подтвердите бронирование</h2>
                <p>Вы собираетесь забронировать это жильё. Пожалуйста, подтвердите.</p>
                <button id="confirm-booking-btn">Подтвердить</button>
                <button id="close-modal-btn">Отмена</button>
            </div>
        </div>


        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2>Чат с арендодателем</h2>

                <div class="chat-box">
                    <div id="chat-messages">
                        <!-- Здесь будут сообщения -->
                    </div>
                </div>

                <div class="chat-input">
                    <input type="text" id="chat-message" placeholder="Введите сообщение...">
                    <button id="send-message">Отправить</button>
                </div>
            </div>
        </div>
        </main>
        <x-footer />
    </div>
    <script src="/js/reportModal.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let bookedDates = @json($bookedDates); // Получаем список забронированных дат

            // ✅ Проверяем, авторизован ли пользователь
            let isAuthenticated = @json(auth()->check());

            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("btn-primary")) {
                    // ✅ Перенаправляем на авторизацию, если пользователь не вошёл
                    if (!isAuthenticated) {
                        event.preventDefault(); // ⛔ Отменяем действие кнопки
                        redirectToLogin();
                    }
                }
            });

            function redirectToLogin() {
                window.location.href = "/login"; // Маршрут авторизации
            }

            function isDateBooked(date) {
                let formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
                return bookedDates.includes(formattedDate);
            }

            // Функция блокировки забронированных дат
            function disableBookedDates(date) {
                return isDateBooked(date) ? [false, "booked-date", "Эта дата уже забронирована"] : [true, "", ""];
            }

            // Инициализация календаря для даты заезда
            $("#check-in").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0,
                beforeShowDay: disableBookedDates,
                onSelect: function (selectedDate) {
                    let minCheckoutDate = new Date(selectedDate);
                    minCheckoutDate.setDate(minCheckoutDate.getDate() + 1);
                    $("#check-out").datepicker("option", "minDate", minCheckoutDate);
                    $("#check-out").datepicker("setDate", minCheckoutDate);
                }
            });

            // Инициализация календаря для даты выезда
            $("#check-out").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 1,
                beforeShowDay: disableBookedDates
            });
        });

    </script>




</body>

</html>