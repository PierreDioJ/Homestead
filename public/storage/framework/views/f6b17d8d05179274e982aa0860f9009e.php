<!DOCTYPE html>
<html lang="ru">

<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
        <main class="main">
            <div class="main-container">
                <!-- Сайдбар -->
                <aside class="sidebar">
                    <nav>
                        <ul>
                            <p class="menu-link-title">Панель управления</p>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.users.index')); ?>">👥
                                    Пользователи</a></li>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.listings.index')); ?>">📌
                                    Объявления</a></li>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.bookings.index')); ?>">📅
                                    Бронирования</a></li>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.payments.index')); ?>">💳
                                    Платежи</a></li>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.stats.index')); ?>">📊
                                    Статистика и аналитика</a></li>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.reports.index')); ?>">📊
                                    Репорты</a></li>
                            <li><a href="#" class="menu-link" data-url="<?php echo e(route('admin.reviews.index')); ?>">📊
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
                            <p><?php echo e($totalUsers ?? 0); ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>📌 Объявления</h3>
                            <p><?php echo e($totalListings ?? 0); ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>📅 Бронирования</h3>
                            <p><?php echo e($totalBookings ?? 0); ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>💳 Общая сумма платежей</h3>
                            <p><?php echo e(number_format($totalPayments ?? 0, 2, ',', ' ')); ?> ₽</p>
                        </div>
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

</html><?php /**PATH /var/www/html/resources/views/admin/admin.blade.php ENDPATH**/ ?>