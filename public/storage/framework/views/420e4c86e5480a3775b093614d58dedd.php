<div class="container">
    <h1>📊 Статистика и аналитика</h1>

    <!-- Карточки статистики -->
    <div class="stats-grid">
        <div class="stat-card">👥 Пользователи: <strong><?php echo e($totalUsers); ?></strong></div>
        <div class="stat-card">📢 Объявления: <strong><?php echo e($totalListings); ?></strong></div>
        <div class="stat-card">📆 Бронирования: <strong><?php echo e($totalBookings); ?></strong></div>
        <div class="stat-card">💰 Общая сумма платежей: <strong><?php echo e(number_format($totalRevenue, 2)); ?> ₽</strong></div>
    </div>

    <h2>📉 Графики</h2>
    <div id="bookingsChart" style="width: 100%; height: 400px;"></div>
    <div id="revenueChart" style="width: 100%; height: 400px;"></div>

    <h2>🔥 Топ-10 объявлений по бронированиям</h2>
    <table class="stats-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Бронирования</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $topListings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($listing->id); ?></td>
                    <td><?php echo e($listing->title); ?></td>
                    <td><?php echo e($listing->bookings_count); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <h2>🏆 Топ-10 пользователей по платежам</h2>
    <table class="stats-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Сумма платежей</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e(number_format($user->payments_sum_amount, 2)); ?> ₽</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<!-- Скрипт для подключения Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js" async></script>

<script type="text/javascript">
    console.log("Загружаем Google Charts...");

    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(function() {
        console.log("Google Charts загружен успешно!");
        drawCharts();
    });

    function drawCharts() {
        console.log("bookingsPerMonth:", <?php echo json_encode($bookingsPerMonth, 15, 512) ?>);
        console.log("revenuePerMonth:", <?php echo json_encode($revenuePerMonth, 15, 512) ?>);

        // График для бронирований
        var bookingsData = new google.visualization.DataTable();
        bookingsData.addColumn('string', 'Месяц');
        bookingsData.addColumn('number', 'Бронирования');
        bookingsData.addRows([
            <?php $__currentLoopData = $bookingsPerMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                ["<?php echo e($month); ?>", <?php echo e($count); ?>],
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ]);

        // График для дохода
        var revenueData = new google.visualization.DataTable();
        revenueData.addColumn('string', 'Месяц');
        revenueData.addColumn('number', 'Доход');
        revenueData.addRows([
            <?php $__currentLoopData = $revenuePerMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                ["<?php echo e($month); ?>", <?php echo e($sum); ?>],
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        ]);

        // Настройка графиков
        var bookingsChart = new google.visualization.LineChart(document.getElementById('bookingsChart'));
        var revenueChart = new google.visualization.LineChart(document.getElementById('revenueChart'));

        // Опции для графиков
        var optionsBookings = {
            title: 'Бронирования по месяцам',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: { 
                title: 'Месяц',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            vAxis: { 
                title: 'Количество',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            series: {
                0: { color: '#1f77b4', lineWidth: 3 },
            },
            pointSize: 5, 
        };

        var optionsRevenue = {
            title: 'Доход по месяцам',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: { 
                title: 'Месяц',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            vAxis: { 
                title: 'Доход (₽)',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            series: {
                0: { color: '#ff7f0e', lineWidth: 3 },
            },
            pointSize: 5,
        };

        // Рисуем графики
        bookingsChart.draw(bookingsData, optionsBookings);
        revenueChart.draw(revenueData, optionsRevenue);
    }
</script>

<style>
    .stats-grid {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .stats-table th, .stats-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .stats-table th {
        background: #343a40;
        color: white;
    }

    #bookingsChart, #revenueChart {
        max-width: 100%;
        margin-top: 20px;
    }
</style>
<?php /**PATH /var/www/html/resources/views/admin/stats.blade.php ENDPATH**/ ?>