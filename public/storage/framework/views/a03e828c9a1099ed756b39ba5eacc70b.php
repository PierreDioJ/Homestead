<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Homestead | Личный кабинет</title>
    <link rel="stylesheet" href="/css/styles-profile.css">
    <link rel="stylesheet" href="/css/styles-general.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
        <main class="main-page grid--2 container">
            <div class="left-side-profile">
                <h2 class="profile-welcome">Добро пожаловать, <?php echo e($user->name); ?></h2>

                <?php if($user->isLandlord()): ?>
                    <div class="profile-listings">
                        <h2 class="profile-listings-title">Ваши объявления</h2>
                        <?php if($listings->isEmpty()): ?>
                            <p class="profile-listings-text">У вас пока нет объявлений.</p>
                        <?php else: ?>
                            <div class="listings-grid">
                                <?php $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="listing-card">
                                        <a href="<?php echo e(route('listings.show', $listing->id)); ?>" target="_blank">
                                            <img src="<?php echo e(Storage::url($listing->photo)); ?>" alt="Фото объявления"
                                                class="listing-card-img">
                                            <div class="listing-card-info">
                                                <h4><?php echo e($listing->title); ?></h4>
                                                <p><?php echo e($listing->location); ?></p>
                                                <p><?php echo e($listing->price); ?> ₽/ночь</p>
                                            </div>
                                        </a>

                                        <div class="listing-actions">
                                            <a href="<?php echo e(route('listings.edit', $listing->id)); ?>" class="btn-edit">Редактировать</a>

                                            <form action="<?php echo e(route('listings.delete', $listing->id)); ?>" method="POST"
                                                onsubmit="return confirm('Вы уверены, что хотите удалить это объявление?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn-delete">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>


            <div class="right-side-profile">
                <?php if(session('success')): ?>
                    <p class="success-message"><?php echo e(session('success')); ?></p>
                <?php endif; ?>
                <form action="/profile" method="POST" class="profile-form">
                    <h2 class="form-title">Это ваш профиль</h2>
                    <?php echo csrf_field(); ?>
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>

                    <label for="password">Новый пароль (если хотите изменить):</label>
                    <input type="password" id="password" name="password">

                    <label for="password_confirmation">Подтвердите новый пароль:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">

                    <button type="submit" class="btn-submit">Обновить</button>
                </form>
                <button type="button" class="btn-bookings">Мои бронирования</button>
                <button type="submit" class="btn-favorite">Избранные объявления</button>
                <?php if($errors->any()): ?>
                    <div class="error">
                        <ul class="error-list">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="error-text"><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <div id="bookingsModal" class="modal">
            <div class="modal-content">
                <span class="btn-close" id="closeBookings">&times;</span>
                <h2>Мои бронирования</h2>
                <div class="bookings-grid">
                    <?php if($bookings->isEmpty()): ?>
                        <p class="no-bookings">У вас пока нет бронирований.</p>
                    <?php else: ?>
                                    <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $checkIn = \Carbon\Carbon::parse($booking->check_in_date);
                                                        $checkOut = \Carbon\Carbon::parse($booking->check_out_date);
                                                        $nights = $checkIn->diffInDays($checkOut);
                                                        $totalPrice = $nights * $booking->listing->price;
                                                    ?>

                                                    <div class="booking-item">
                                                        <img class="booking-img" src="<?php echo e(asset('storage/' . $booking->listing->photo)); ?>"
                                                            alt="Бронирование">
                                                        <div class="booking-details">
                                                            <h3><?php echo e($booking->listing->title); ?></h3>
                                                            <p>Дата: <?php echo e($checkIn->format('d.m.Y')); ?> - <?php echo e($checkOut->format('d.m.Y')); ?></p>
                                                            <p>Цена: <?php echo e(number_format($booking->listing->price, 0, '', ' ')); ?> ₽/ночь</p>
                                                            <p>Итого: <?php echo e(number_format($totalPrice, 0, '', ' ')); ?> ₽</p>
                                                        </div>
                                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
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
    <script src="/js/favoritesModal.js"></script>
    <script src="/js/bookingsModal.js"></script>
    <script src="/js/listingLimit.js"></script>
</body>

</html><?php /**PATH /var/www/html/resources/views/profile.blade.php ENDPATH**/ ?>