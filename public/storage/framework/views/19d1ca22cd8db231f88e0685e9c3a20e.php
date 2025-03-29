<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Жилье</title>
    <link rel="stylesheet" href="/css/styles-welcome.css">
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
        <section class="filter">
            <div class="container">
                <div class="main-filter">
                    <h1 class="title-filter">Снять жильё в России на краткосрочной основе</h1>
                    <p class="number-offers"><span class="number"><?php echo e($listings->count()); ?></span>&nbsp;предложения</p>
                    <div class="filter-form">
                        <form action="<?php echo e(route('listings.index')); ?>" method="get" class="form-filter">
                            <div class="form-city">
                                <label for="city" class="label-city">Где</label>
                                <input type="text" name="city" id="city" placeholder="Поиск направлений"
                                    value="<?php echo e(request('city')); ?>" />
                            </div>
                            <div class="form-date-check-in">
                                <label for="date-check-in" class="label-check-in">Заезд</label>
                                <input type="date" name="date-check-in" id="date-check-in"
                                    value="<?php echo e(request('date-check-in')); ?>" placeholder="Когда?" />
                            </div>
                            <div class="form-date-departure">
                                <label for="date-departure" class="label-departure">Отъезд</label>
                                <input type="date" name="date-departure" id="date-departure"
                                    value="<?php echo e(request('date-departure')); ?>" placeholder="Когда?" />
                            </div>
                            <div class="form-people">
                                <label for="people" class="label-people">Гости</label>
                                <input type="number" name="people" id="people" min="1" value="<?php echo e(request('people')); ?>"
                                    placeholder="Кто едет?" />
                            </div>
                            <div class="form-price">
                                <label for="max_price" class="label-price">Макс. цена</label>
                                <input type="number" name="max_price" id="max_price" min="0"
                                    value="<?php echo e(request('max_price')); ?>" placeholder="Цена до" />
                            </div>
                            <div class="form-submit">
                                <img class="search-filter" src="../img/search-filter.png" />
                                <input type="submit" value="" class="form-submit-filter" />
                            </div>
                        </form>
                        <p class="advantage-rent">Краткосрочная аренда жилья — идеальное решение для туристов и
                            командированных</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="offers">
            <div class="container">
                <div class="header-sort">
                    <h3 class="number-offers-find">
                        <span class="number">НАЙДЕНО ПРЕДЛОЖЕНИЙ: <?php echo e($listings->total() ?? 0); ?></span>
                    </h3>
                    <form method="GET" action="<?php echo e(route('listings.index')); ?>">
    <select id="sort" name="sort" onchange="this.form.submit()">
        <option value="" disabled selected>Способ сортировки</option>
        <option value="by_rating" <?php echo e(request('sort') == 'by_rating' ? 'selected' : ''); ?>>По рейтингу</option>
        <option value="cheaper" <?php echo e(request('sort') == 'cheaper' ? 'selected' : ''); ?>>Сначала дешевле</option>
        <option value="more_expensive" <?php echo e(request('sort') == 'more_expensive' ? 'selected' : ''); ?>>Сначала дороже</option>
    </select>
</form>

                </div>

                <div class="listings-list">
                    <div class="listings">
                        <?php $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('listings.show', $listing->id)); ?>" class="listing-card">
                                <div class="listing-image-wrapper">
                                    <button class="favorite-btn" data-id="<?php echo e($listing->id); ?>">♡</button>
                                    <img src="<?php echo e(asset('storage/' . $listing->photo)); ?>" alt="Фото" class="listing-img">

                                    
                                    <?php if($listing->reviews_count >= 10 && $listing->average_rating >= 4.5): ?>
                                        <div class="listing-badge">Выбор гостей</div>
                                    <?php endif; ?>
                                    <span class="listing-rating">
                                        <i class="icon-star"></i> &#9733;
                                        <?php echo e(number_format($listing->average_rating, 2)); ?>

                                    </span>
                                </div>
                                <div class="listing-info">
                                    <div class="listing-header">
                                        <h3 class="listing-title"><?php echo e($listing->title); ?></h3>
                                    </div>
                                    <p class="listing-location"><?php echo e($listing->location); ?></p>
                                    <p class="listing-details">Ближайшая свободная дата:
                                        <strong><?php echo e($listing->nextAvailableDate ? $listing->nextAvailableDate : 'Нет доступных дат'); ?></strong>
                                    <p class="listing-price"><?php echo e($listing->price); ?> ₽ / ночь</p>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>


                </div>

            </div>
        </section>
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
    <script src="/js/favorites.js" defer></script>
    <script src="<?php echo e(asset('js/sort.js')); ?>"></script>
</body>

</html><?php /**PATH /var/www/html/resources/views/welcome.blade.php ENDPATH**/ ?>