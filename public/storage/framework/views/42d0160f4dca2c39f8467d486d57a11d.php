

<?php $__env->startSection('content'); ?>
    <h1>Загрузка данных...</h1>
    <?php dd($bookingsData, $revenueData, $topListings, $topUsers); ?>

<div class="container">
    <h1>📊 Статистика и аналитика</h1>

    <div class="grid">
        <!-- График бронирований -->
        <div class="card">
            <h3>📅 Бронирования по месяцам</h3>
            <canvas id="bookingsChart"></canvas>
        </div>

        <!-- График дохода -->
        <div class="card">
            <h3>💰 Доход по месяцам</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <!-- Топ-10 объявлений -->
        <div class="card">
            <h3>🔥 Топ-10 популярных объявлений</h3>
            <ul>
                <?php $__currentLoopData = $topListings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($listing->title); ?> — <?php echo e($listing->bookings_count); ?> бронирований</li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        <!-- Топ-10 пользователей -->
        <div class="card">
            <h3>🏆 Топ-10 пользователей</h3>
            <ul>
                <?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($user->name); ?> — <?php echo e($user->bookings_count); ?> бронирований</li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>

<!-- Подключаем Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // График бронирований
    var bookingsChart = new Chart(document.getElementById('bookingsChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($bookingsData->pluck('month')); ?>,
            datasets: [{
                label: 'Бронирования',
                data: <?php echo json_encode($bookingsData->pluck('count')); ?>,
                borderColor: 'blue',
                fill: false
            }]
        }
    });

    // График дохода
    var revenueChart = new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($revenueData->pluck('month')); ?>,
            datasets: [{
                label: 'Доход (₽)',
                data: <?php echo json_encode($revenueData->pluck('total')); ?>,
                backgroundColor: 'green'
            }]
        }
    });
</script>

<style>
   .container {
    max-width: 1200px;
    margin: auto;
}

.grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

</style>


<?php $__env->stopSection(); ?>
<?php /**PATH /var/www/html/resources/views/admin/analytics.blade.php ENDPATH**/ ?>