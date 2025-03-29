<header class="header">
    <section class="header-page">
        <div class="container">
            <div class="header-nav-page">
                <a href="/" class="logo-link">
                    <img src="../img/Logo-header.png" class="logo-header" alt="Homestead Logo">
                </a>
                <nav class="header-nav">
                    <ul class="nav-list__header">
                        <?php if(auth()->guard()->check()): ?>
                            <li class="nav-item__header">
                                <a href="<?php echo e(route('listings.index')); ?>" class="nav-link__header">Просмотр жилья</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="<?php echo e(route('chat.list')); ?>" class="nav-link__header">Мои чаты</a>
                            </li>
                            <?php if(Auth::user()->isLandlord()): ?>
                                <li class="nav-item__header">
                                    <a href="<?php echo e(route('listings.create')); ?>" class="nav-link__header">Сдать жильё</a>
                                </li>
                            <?php endif; ?>
                            <?php if(Auth::user()->isAdmin()): ?>
                                <li class="nav-item__header">
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link__header">Панель администратора</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item__header">
                                <a href="/profile" class="nav-link__header">Привет, <?php echo e(Auth::user()->name); ?></a>
                            </li>
                            <li class="nav-item__header">
                                <a href="<?php echo e(route('logout')); ?>" class="nav-link__header"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Выйти
                                </a>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        <?php endif; ?>
                        <?php if(auth()->guard()->guest()): ?>
                            <li class="nav-item__header">
                                <a href="<?php echo e(route('listings.index')); ?>" class="nav-link__header">Просмотр жилья</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="/login" class="nav-link__header">Войти</a>
                            </li>
                            <li class="nav-item__header">
                                <a href="/register" class="nav-link__header">Регистрация</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</header><?php /**PATH /var/www/html/resources/views/components/header.blade.php ENDPATH**/ ?>