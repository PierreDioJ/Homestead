<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Homestead | Создать объявление</title>
    <link rel="stylesheet" href="/css/styles-create-listings.css">
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
    <main class="main-page grid--2 container">
        <div class="left-side-create">
            <h2 class="create-title">Создать объявление</h2>

            <?php if(session('success')): ?>
                <div class="alert">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
        </div>
        <div class="create-box">
            <form action="<?php echo e(route('listings.store')); ?>" method="POST" enctype="multipart/form-data">
                <h2 class="form-title">Ваше объявление</h2>
                <?php echo csrf_field(); ?>

                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" value="<?php echo e(old('title')); ?>" required>

                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="4" required><?php echo e(old('description')); ?></textarea>

                <label for="location">Местоположение</label>
                <input type="text" id="location" name="location" value="<?php echo e(old('location')); ?>" required>

                <label for="price">Цена за ночь (₽)</label>
                <input type="number" id="price" name="price" value="<?php echo e(old('price')); ?>" required>

                <label for="guests">Максимум гостей</label>
                <input type="number" id="guests" name="guests" value="<?php echo e(old('guests')); ?>" required>

                <label for="photo">Фото</label>
                <input type="file" id="photo" name="photo" accept="image/*">

                <button type="submit">Создать</button>
            </form>
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
</body>

</html><?php /**PATH /var/www/html/resources/views/listings/create.blade.php ENDPATH**/ ?>