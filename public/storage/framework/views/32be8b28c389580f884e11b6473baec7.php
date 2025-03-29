<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
    <?php if (isset($component)) { $__componentOriginal2a2e454b2e62574a80c8110e5f128b60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a2e454b2e62574a80c8110e5f128b60 = $attributes; } ?>
<?php $component = App\View\Components\Header::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Header::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a2e454b2e62574a80c8110e5f128b60)): ?>
<?php $attributes = $__attributesOriginal2a2e454b2e62574a80c8110e5f128b60; ?>
<?php unset($__attributesOriginal2a2e454b2e62574a80c8110e5f128b60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a2e454b2e62574a80c8110e5f128b60)): ?>
<?php $component = $__componentOriginal2a2e454b2e62574a80c8110e5f128b60; ?>
<?php unset($__componentOriginal2a2e454b2e62574a80c8110e5f128b60); ?>
<?php endif; ?>
    <div class="wrapper">
        <div class="container">
            <main class="main">
                <div class="main-grid">
                    <div>
                        <div class="header-listing">
                            <h1 class="listing-title"><?php echo e($listing->title); ?></h1>
                            <p class="listing-location"><?php echo e($listing->location); ?></p>
                        </div>
                        <div class="gallery">
                            <img src="<?php echo e(asset('storage/' . $listing->photo)); ?>" alt="Фото-1" class="listing-img_1">
                            <img src="<?php echo e(asset('storage/' . $listing->photo)); ?>" alt="Фото-2" class="listing-img_2">
                            <img src="<?php echo e(asset('storage/' . $listing->photo)); ?>" alt="Фото-3" class="listing-img_3">
                            <img src="<?php echo e(asset('storage/' . $listing->photo)); ?>" alt="Фото-4" class="listing-img_4">
                        </div>
                        <button type="submit" class="show-img">Показать все фото</button>
                        <div class="info">
                            <h2 class="title-info">О жилье</h2>
                            <p class="desc-info"><?php echo e($listing->description); ?></p>
                            <ul class="desc-list">
                                <li class="desc-item"><?php echo e($listing->guests); ?> гостей</li>
                                <li class="desc-item"><?php echo e($listing->location); ?></li>
                            </ul>
                            <h2>Отзывы</h2>

                            <!-- Форма для отправки отзыва -->
                            <?php if(auth()->guard()->check()): ?>
                                <form action="<?php echo e(route('reviews.store', $listing->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
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
                            <?php else: ?>
                                <p><a href="<?php echo e(route('login')); ?>">Войдите</a>, чтобы оставить отзыв.</p>
                            <?php endif; ?>

                            <!-- Вывод существующих отзывов -->
                            <?php if($listing->reviews->count() > 0): ?>
                                <?php $__currentLoopData = $listing->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="review">
                                        <p><strong><?php echo e($review->user->name); ?></strong> (<?php echo e($review->rating); ?>⭐️)</p>
                                        <p><?php echo e($review->comment); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <p>Пока отзывов нет.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="details">
                        <h2><?php echo e($listing->price); ?> ₽ / ночь</h2>
                        <p>Ближайшая свободная дата:
                            <strong><?php echo e($nextAvailableDate ? $nextAvailableDate : 'Нет доступных дат'); ?></strong>
                        </p>
                        <button type="submit" class="report-btn">&#9873; Пожаловаться на объявление</button>

                        <div id="reportModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <h2>Пожаловаться на объявление</h2>
                                <form id="reportForm" data-listing-id="<?php echo e($listing->id); ?>">
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

                        <form action="<?php echo e(route('payment.create')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

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
                                    <?php for($i = 1; $i <= $listing->guests; $i++): ?>
                                                                        <option value="<?php echo e($i); ?>" <?php echo e(old('guests') == $i ? 'selected' : ''); ?>>
                                                                            <?php
                                                                                // Логика для склонения
                                                                                if ($i == 1) {
                                                                                    $guestText = 'гость';
                                                                                } elseif ($i >= 2 && $i <= 4) {
                                                                                    $guestText = 'гостя';
                                                                                } else {
                                                                                    $guestText = 'гостей';
                                                                                }
                                                                            ?>
                                                                            <?php echo e($i); ?> <?php echo e($guestText); ?>

                                                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <input type="hidden" name="amount" value="<?php echo e($listing->price); ?>">
                            <input type="hidden" name="listing_id" value="<?php echo e($listing->id); ?>">

                            <?php if(auth()->check()): ?>
                                <button type="submit" class="btn btn-primary">Оплатить</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary" onclick="redirectToLogin()">Оплатить</button>
                            <?php endif; ?>
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
        <?php if (isset($component)) { $__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa = $attributes; } ?>
<?php $component = App\View\Components\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Footer::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa)): ?>
<?php $attributes = $__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa; ?>
<?php unset($__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa)): ?>
<?php $component = $__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa; ?>
<?php unset($__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa); ?>
<?php endif; ?>
    </div>
    <script src="/js/reportModal.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let bookedDates = <?php echo json_encode($bookedDates, 15, 512) ?>; // Получаем список забронированных дат

            // ✅ Проверяем, авторизован ли пользователь
            let isAuthenticated = <?php echo json_encode(auth()->check(), 15, 512) ?>;

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

</html><?php /**PATH /var/www/html/resources/views/listings/show.blade.php ENDPATH**/ ?>